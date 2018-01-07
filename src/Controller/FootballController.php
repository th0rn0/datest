<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;
use FootballInterface\Transformer\ResponseTransformer;
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
        return $this->twig->render('football/show.html.twig', array(
            'event' => ResponseTransformer::transformFootballEvent($response->getBody()->getContents())
        ));
    }
}