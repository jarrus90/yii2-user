<?php

return \yii\helpers\ArrayHelper::merge(require_once __DIR__ . '/common.php', [
    'id' => 'yii2-user-console',
    'aliases' => [],
    'controllerMap' => [
        'migrate' => [
            'class' => 'jarrus90\Core\Console\MigrateController',
            'migrationPath' => '@baseApp/migrations'
        ],
    ],
    'components' => [
        'log'   => null,
        'cache' => null,
        'db'    => require __DIR__ . '/db.php',
    ],
]);
