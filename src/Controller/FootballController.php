<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class FootballController
{
	protected $twig;

	protected $client;

    public function __construct($eventId, $twig, Client $client)
    {
        $this->twig = $twig;
        $this->client = $client;
    }

    public function show($eventId)
    {
        $url = getenv('API_URL') . '/sportsbook/event/' . $eventId;

        $response = $this->client->get($url, ['http_errors' => false]);
        if ($response->getStatusCode() != 200) {
            return $this->twig->render('errors/'. $response->getStatusCode() . '.html.twig');
        }
        $body = json_decode($response->getBody()->getContents());

        $return['name']                   = $body->event->name;
        $return['id']                     = $body->event->eventId;
        $return['displayOrder']           = $body->event->displayOrder;
        $return['linkedEventTypeName']    = $body->event->linkedEventTypeName;
        $return['scores']                 = $body->event->scores;
        foreach ($body->event->competitors as $competitor) {
            if (strtolower($competitor->position) == 'away') {
                $return['competitors']['away'] = $competitor->name; 
            }
            if (strtolower($competitor->position) == 'home') {
                $return['competitors']['home'] = $competitor->name; 
            }
        }
        $return['startTime']              = $body->event->startTime;
        $return['status']                 = $body->event->status;
        foreach ($body->markets->$eventId as $market) {
            if (!isset($body->outcomes->{$market->marketId})) {
                $url = getenv('API_URL') . '/sportsbook/market/' . $market->marketId;
                $response = $this->client->get($url);
                $body = json_decode($response->getBody()->getContents());
            }
            $outcomes = array();
            foreach ($body->outcomes->{$market->marketId} as $outcome) {
                if ($market->type == 'correct-score') {
                    $outcomes[$outcome->type][] = $outcome;
                } else {
                    $outcomes[] = $outcome;
                }
            }
            $market->outcomes = $outcomes;
            $return['markets'][] = $market;
        }

        return $this->twig->render('football/show.html.twig', array(
            'event' => $return
        ));
    }
}