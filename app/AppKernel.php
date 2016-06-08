<?php

use Symfony\Component\HttpFoundation\Request;

$app->register(new \Silex\Provider\DoctrineServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());
$app->register(new \Silex\Provider\LocaleServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider());
$app->register(new \Silex\Provider\SecurityServiceProvider());
$app->register(new \Silex\Provider\AssetServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider());

if ($app['env'] === 'dev') {
    $app->register(new \Silex\Provider\VarDumperServiceProvider());
    $app->register(new \Silex\Provider\HttpFragmentServiceProvider());
    $app->register(new \Silex\Provider\ServiceControllerServiceProvider());
    $app->register(new \Silex\Provider\WebProfilerServiceProvider());
}

require __DIR__.'/config/config_'.$app['env'].'.php';

$app->before(
    function (Request $request) use ($app) {
        $app['session']->start();
    }
);

return $app;


