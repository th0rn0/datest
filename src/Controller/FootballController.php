<?php

namespace FootballInterface\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class FootballController
{
	protected $twig;

	protected $client;

    public function __construct($twig, Client $client)
    {
        $this->twig = $twig;
        $this->client = $client;
    }

    public function show($eventId)
    {

    }
}