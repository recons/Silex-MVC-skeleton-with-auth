<?php

$app['controllers']->assert('id', '\d+');

$app->mount('/', new Controller\IndexController());
$app->mount('/user', new Controller\UserController());
$app->mount('/groups', new Controller\GroupController());
$app->mount('/subjects', new Controller\SubjectController());
$app->mount('/people', new Controller\HumanController());
$app->mount('/marks', new Controller\MarkController());