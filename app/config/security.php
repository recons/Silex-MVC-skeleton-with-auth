<?php

$app['security.firewalls'] = [
    'assets' => [
        'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
        'security' => false
    ],
    'login' => [
        'pattern' => '^/user/login$',
        'anonymous' => true
    ],
    'default' => [
        'pattern' => '^/',
        'form' => [
            'login_path' => '/user/login',
            'check_path' => '/login_check',
            'username_parameter' => 'form[username]',
            'password_parameter' => 'form[password]',
        ],
        'logout'  => true,
        'anonymous' => false,
        'users' => function () use ($app) {
            return new Repository\UserRepository(
                $app['db'],
                $app['security.encoder_factory']
            );
        },
    ]
];

$app['security.role_hierarchy'] = [
    'ROLE_ADMIN' => ['ROLE_USER']
];