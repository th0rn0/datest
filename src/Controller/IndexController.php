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
        
        return $this->twig->render('index.html.twig');
        return json_encode($response->getBody()->getContents());

        return $this->twig->render('index.html.twig', array(
            'body' => $response->getBody()->getContents(),
            'responseCode' => $response->getStatusCode(),
            'contentLength' => $response->getBody()->getSize(),
        ));
    }
}