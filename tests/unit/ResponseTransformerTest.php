<?php

use Silex\WebTestCase;
use FootballInterface\Transformer\ResponseTransformer;

class ResponseTransformerTest extends WebTestCase
{
    /**
     * @return mixed
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../src/app.php';
        require __DIR__.'/../../config/dev.php';
        require __DIR__.'/../../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }

    public function testTransformFootballEvent()
    {
        $this->assertTrue(true);
    }
    public function testTransformFootballOverview()
    {
        $this->assertTrue(true);
    }
}