<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;
use FootballInterface\Transformer\ResponseTransformer;
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
        return $this->twig->render('index.html.twig', array(
            'events' => ResponseTransformer::transformFootballOverview($response->getBody()->getContents())
        ));
    }
}