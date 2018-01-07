<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app['index.controller'] = function () use ($app) {
    return new \FootballInterface\Controller\IndexController(
        $app['twig'], $app['client'], new \FootballInterface\Transformer\ResponseTransformer()
    );
};

$app['football.controller'] = function () use ($app) {
    return new \FootballInterface\Controller\FootballController(
        $eventId, $app['twig'], $app['client'], new \FootballInterface\Transformer\ResponseTransformer()
    );
};

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

// Routes
$app->get('/', 'index.controller:index');
$app->get('/football/{eventId}', 'football.controller:show');
