Several common basic functionality for personal modules

###Requirements

YII 2.0

###Installation

~~~php

"require": {
    "jarrus90/yii2-core": "*",
},

php composer.phar update

~~~

#Console controllers

###Migration
[Thanks to dmstr](https://github.com/dmstr/yii2-migrate-command)

Console Migration Command with multiple paths/aliases support
~~~php
    'controllerMap'       => [
		'migrate' => [
			'class' => 'jarrus90\Core\Console\MigrateController'
		],
	],
~~~
###Assets cleanup
[Thanks to assayer-pro](https://github.com/assayer-pro/yii2-asset-clean)

Yii2 console command to clean web/assets/ directory
~~~php
	'controllerMap' => [
		'asset' => [
			'class' => 'jarrus90\Core\Console\AssetController',
		],
	],
~~~

#Components
###Multilang request
Sets current user language as application language
~~~php
    'components' => [
        'request' => [
            'class' => 'jarrus90\Core\components\MultilangRequest',
            'cookieValidationKey' => 'gdsgsgsB^T#Rb'
        ],
	]
~~~
Requires user identity having field `lang`
