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
            $return[$eventId]['id']                     = $event->eventId;
            $return[$eventId]['displayOrder']           = $event->displayOrder;
            $return[$eventId]['linkedEventTypeName']    = $event->linkedEventTypeName;
            $return[$eventId]['scores']                 = $event->scores;
            $return[$eventId]['competitors']            = $event->competitors;
            $return[$eventId]['startTime']              = $event->startTime;
            foreach ($body->markets as $marketEventId => $markets) {
                if ($marketEventId == $eventId) {
                    $return[$eventId]['primaryMarket'] = $markets{0};
                }
            }
            foreach ($body->outcomes as $outcomeMarketId => $outcomes) {
                if ($outcomeMarketId == $return[$eventId]['primaryMarket']->marketId) {
                    $return[$eventId]['outcomes'] = $outcomes;
                }
            }
        }

        //DEBUG
        // dump($body->events);
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