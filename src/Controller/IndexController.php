<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
	protected $twig;

	protected $client;

    public function __construct($twig, Client $client)
    {
        $this->twig = $twig;
        $this->client = $client;
    }

    public function index(Request $request) {


        $url = getenv('API_URL') . '/football/live?primaryMarkets=true';
        
        //DEBUG
        // $primaryMarket = true;

        // if ($request->query->get('markets')) {
        //     $url = $url . '?primaryMarkets=true';
        // }

        $response = $this->client->get($url);
        $body = json_decode($response->getBody()->getContents());


        // Build array for view
        foreach ($body->events as $event) {
            $eventId = $event->eventId;
            $return[$eventId]['name']                   = $event->name;
            $return[$eventId]['displayOrder']           = $event->displayOrder;
            $return[$eventId]['linkedEventTypeName']    = $event->linkedEventTypeName;
            $return[$eventId]['scores']                 = $event->scores;
            $return[$eventId]['competitors']            = $event->competitors;
            $return[$eventId]['startTime']              = $event->startTime;
            foreach ($body->markets as $markets) {
                foreach ($markets as $market) {
                    if ($market->eventId == $event->eventId) {
                        $return[$eventId]['primaryMarket'] = $market;
                    }
                }
            }
            foreach ($body->outcomes as $marketId => $outcomes) {
                if ($marketId == $return[$eventId]['primaryMarket']->marketId) {
                    $return[$eventId]['outcomes'] = $outcomes;
                }
            }
        }

        //DEBUG
        // dump($return);
        // dump($body->outcomes);
        // die();

        // Sort the events display order
        usort($return, function($a, $b)
        {
            return $a['displayOrder'] < $b['displayOrder'];
            //return strcmp($a->displayOrder, $b->displayOrder);
        });

        return $this->twig->render('index.html.twig', array(
            'events' => $return
        ));

        return json_encode($response->getBody()->getContents());

        return $this->twig->render('index.html.twig', array(
            'body' => $response->getBody()->getContents(),
            'responseCode' => $response->getStatusCode(),
            'contentLength' => $response->getBody()->getSize(),
        ));
    }
}