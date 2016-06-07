<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 30.05.16
 * Time: 23:17
 */

$app['cache.path'] = __DIR__ . '/../../var/cache';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

$app['db.options'] = array(
    'driver'	=> 'oci8',
    'host'		=> 'localhost',
    'port'      => 1521,
    'dbname'	=> 'stud',
    'user'		=> 'sergey',
    'password'	=> '123456',
    'charset'   => 'AL32UTF8',
);