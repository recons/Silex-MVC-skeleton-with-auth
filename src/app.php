<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 30.05.16
 * Time: 22:10
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/** @var \Silex\Application $app */
$app->register(new \Silex\Provider\DoctrineServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());
$app->register(new \Silex\Provider\LocaleServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider());
$app->register(new \Silex\Provider\SecurityServiceProvider(), [
    'security.firewalls' => [
        'assets' => [
            'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
            'security' => false
        ],
        'login' => [
            'pattern' => '^/user/login$',
            'anonymous' => true
        ],
        'admin' => [
            'pattern' => '^/',
            'form' => [
                'login_path' => '/user/login',
                'check_path' => '/login_check',
                'username_parameter' => 'form[username]',
                'password_parameter' => 'form[password]',
            ],
            'logout'  => true,
            'anonymous' => false,
            'users' => $app->factory(function () use ($app) {
                return new Repository\UserRepository($app['db'], $app['security.encoder_factory']);
            }),
        ],
    ],
    'security.role_hierarchy' => [
        'ROLE_ADMIN' => ['ROLE_USER'],
    ],
]);

$app->register(new \Silex\Provider\AssetServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.options' => [
        'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true,
    ],
    'twig.form.templates' => ['form_div_layout.html.twig', 'common/form_div_layout.html.twig'],
    'twig.path' => [__DIR__ . '/../app/views']
]);

$app->register(new \Silex\Provider\VarDumperServiceProvider());
$app->register(new \Silex\Provider\HttpFragmentServiceProvider());
$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \Silex\Provider\WebProfilerServiceProvider(), [
    'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    'profiler.mount_prefix' => '/_profiler',
]);

$app['repository.user'] = $app->factory(function ($app) {
    return new Repository\UserRepository($app['db'], $app['security.encoder_factory']);
});

$app['repository.group'] = $app->factory(function ($app) {
    return new Repository\GroupRepository($app['db']);
});

$app['repository.subject'] = $app->factory(function ($app) {
    return new Repository\SubjectRepository($app['db']);
});

$app['repository.human'] = $app->factory(function ($app) {
    return new Repository\HumanRepository($app['db'], $app['repository.group']);
});

$app['repository.mark'] = $app->factory(function ($app) {
    return new Repository\MarkRepository($app['db'], $app['repository.human'], $app['repository.subject']);
});

$app->before(function (Request $request) use ($app) {
    $protected = [
        '/user/login' => 'IS_AUTHENTICATED_ANONYMOUSLY',
    ];
    $path = $request->getPathInfo();

    foreach ($protected as $protectedPath => $role) {
        if (strpos($path, $protectedPath) !== false && !$app['security.authorization_checker']->isGranted($role)) {
            throw new AccessDeniedException();
        }
    }
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }
    return new Response($message, $code);
});

return $app;


