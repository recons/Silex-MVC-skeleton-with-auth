<?php

$app['cache.path'] = __DIR__ . '/../../var/cache/' . $app['env'];

$app['twig.options'] = [
    'cache' => $app['cache.path'] . '/twig',
    'strict_variables' => true
];
$app['twig.form.templates'] = [
    'form_div_layout.html.twig',
    'common/form_div_layout.html.twig'
];
$app['twig.path'] = [__DIR__ . '/../../src/Resources/views'];

$app['db.options'] = [
    'driver' => 'oci8',
    'host' => 'localhost',
    'port' => 1521,
    'dbname' => 'stud',
    'user' => 'sergey',
    'password' => '123456',
    'charset' => 'AL32UTF8',
];

$app['repository.user'] = function ($app) {
    return new Repository\UserRepository(
        $app['db'],
        $app['security.encoder_factory']
    );
};

$app['repository.group'] = function ($app) {
    return new Repository\GroupRepository($app['db']);
};

$app['repository.subject'] = function ($app) {
    return new Repository\SubjectRepository($app['db']);
};

$app['repository.human'] = function ($app) {
    return new Repository\HumanRepository($app['db'], $app['repository.group']);
};

$app['repository.mark'] = function ($app) {
    return new Repository\MarkRepository(
        $app['db'],
        $app['repository.human'],
        $app['repository.subject']
    );
};

require __DIR__.'/routing.php';
require __DIR__.'/security.php';