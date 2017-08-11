<?php

return \yii\helpers\ArrayHelper::merge(require_once __DIR__ . '/common.php', [
    'id' => 'yii2-user-console',
    'aliases' => [],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@baseApp/migrations'
        ],
    ],
    'components' => [
        'log'   => null,
        'cache' => null,
        'db'    => require __DIR__ . '/db.php',
    ],
]);
