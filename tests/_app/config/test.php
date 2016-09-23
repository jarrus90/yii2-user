<?php

return \yii\helpers\ArrayHelper::merge(require_once __DIR__ . '/common.php', [
    'id' => 'yii2-user-tests',
    'aliases' => [
        '@bower' => VENDOR_DIR . '/bower-asset',
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
    'params' => [],
]);