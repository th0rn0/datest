<?php

use Silex\WebTestCase;

class FootballControllerTest extends WebTestCase
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

    public function testShow()
    {
        $this->assertTrue(true);
    }
}