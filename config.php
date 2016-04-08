<?php

/*
 * User module config
 */
return [
    'bootstrap' => ['app\modules\User\Bootstrap'],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/modules/User/views' => [
                        '@style/layouts/user',
                        '@app/modules/User/views', // Override
                    ]
                ]
            ],
        ],
        'urlManager' => [
            'rules' => [
                '/user/<action:(resend|login|logout|confirm)>' => 'user/auth/<action>',
                '/user/settings/profile' => 'user/settings/profile',
                '/user/settings/private' => 'user/settings/private',
                '/user/settings/security' => 'user/settings/security',
                '/user/auth/social' => 'user/auth/social',
                '/user/recovery/<id:\d+>/<code:\w+>' => 'user/recovery/reset',
                
                '/admin/login' => 'user/adminuser/login',
                '/admin/users' => 'user/adminuser/index',
                '/admin/users/<id:\d+>/<action:(profile|roles|delete|block|blockpopup)>' => 'user/adminuser/<action>',
                '/admin/user/role/<action:\w+>' => 'user/adminrole/<action>',
            ]
        ]
    ],
    'modules' => [
        'user' => [
            'class' => 'app\modules\User\Module',
            'controllerMap' => [
                'auth' => 'app\modules\User\Controllers\AuthController',
                'settings' => 'app\modules\User\Controllers\PanelController',
                'recovery' => 'app\modules\User\Controllers\RecoveryController',
                'registration' => 'app\modules\User\Controllers\RegistrationController',
                'adminuser' => 'app\modules\User\Controllers\Admin\UserController',
                'adminrole' => 'app\modules\User\Controllers\Admin\RoleController',
                'adminpermission' => 'app\modules\User\Controllers\Admin\PermissionController',
            ],
        ]
    ],
];
