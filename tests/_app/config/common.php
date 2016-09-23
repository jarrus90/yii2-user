<?php

return [
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@jarrus90/Multilang' => dirname(dirname(dirname(__DIR__))),
        '@jarrus90/User' => VENDOR_DIR . '/jarrus90/yii2-user',
        '@tests' => dirname(dirname(__DIR__)),
        '@vendor' => VENDOR_DIR,
    ],
    'bootstrap' => [
        'jarrus90\User\Bootstrap',
    ],
    'modules' => [
        'user' => [
            'class' => 'jarrus90\User\Module'
        ],
        'multilang' => [
            'class' => 'jarrus90\Multilang\Module'
        ],
    ],
    'params' => [],
];