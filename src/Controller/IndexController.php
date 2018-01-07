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

    public function index(Request $request) 
    {
        $url = getenv('API_URL') . '/football/live?primaryMarkets=true';
        
        $response = $this->client->get($url);
        if ($response->getStatusCode() != 200) {
            return $this->twig->render('errors/'. $response->getStatusCode() . '.html.twig');
        }
        $body = json_decode($response->getBody()->getContents());

        foreach ($body->events as $event) {
            $eventId = $event->eventId;
            $return[$eventId]['name']                   = $event->name;
            $return[$eventId]['id']                     = $event->eventId;
            $return[$eventId]['displayOrder']           = $event->displayOrder;
            $return[$eventId]['linkedEventTypeName']    = $event->linkedEventTypeName;
            $return[$eventId]['scores']                 = $event->scores;
            foreach ($event->competitors as $competitor) {
                if (strtolower($competitor->position) == 'away') {
                    $return[$eventId]['competitors']['away'] = $competitor->name; 
                }
                if (strtolower($competitor->position) == 'home') {
                    $return[$eventId]['competitors']['home'] = $competitor->name; 
                }
            }
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

        usort($return, function($a, $b)
        {
            return $a['displayOrder'] < $b['displayOrder'];
        });

        return $this->twig->render('index.html.twig', array(
            'events' => $return
        ));
    }
}