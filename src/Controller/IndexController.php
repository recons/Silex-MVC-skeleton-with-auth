<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 30.05.16
 * Time: 23:31
 */

namespace Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class IndexController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $indexController = $app['controllers_factory'];
        $indexController->get("/", [$this, 'index'])->bind('homepage');

        return $indexController;
    }

    public function index(Application $app)
    {
        $stat = $app['repository.group']->getStatByYear();
        return $app['twig']->render('home/index.html.twig', [
            'labels' => json_encode(array_keys($stat)),
            'data' => json_encode(array_map('floatval', array_values($stat)))
        ]);
    }
}