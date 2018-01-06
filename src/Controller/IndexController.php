<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;

class IndexController
{
	protected $twig;

	protected $client;

    public function __construct($twig, Client $client)
    {
        $this->twig = $twig;
        $this->client = $client;
    }

    public function index() {

        $response = $this->client->get(getenv('API_URL') . '/football/live');
        $body = json_decode($response->getBody()->getContents());
        //DEBUG
        // dump($body->events);
        // die();

        // Sort the events display order
        usort($body->events, function($a, $b)
        {
            return $a->displayOrder < $b->displayOrder;
            //return strcmp($a->displayOrder, $b->displayOrder);
        });

        return $this->twig->render('index.html.twig', array(
            'events' => $body->events
        ));

        return json_encode($response->getBody()->getContents());

        return $this->twig->render('index.html.twig', array(
            'body' => $response->getBody()->getContents(),
            'responseCode' => $response->getStatusCode(),
            'contentLength' => $response->getBody()->getSize(),
        ));
    }
}