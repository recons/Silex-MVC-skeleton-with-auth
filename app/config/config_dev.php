<?php

require __DIR__.'/config.php';

$app['debug'] = true;

$app['profiler.cache_dir'] = $app['cache.path'] . '/profiler';
$app['profiler.mount_prefix'] = '/_profiler';

require __DIR__.'/routing_dev.php';