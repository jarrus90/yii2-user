<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jarrus90\User;

use Yii;
use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use jarrus90\User\Components\DbManager;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface {

    private $_modelMap = [
        'User'             => 'jarrus90\User\models\User',
        'Account'          => 'jarrus90\User\models\Account',
        'Profile'          => 'jarrus90\User\models\Profile',
        'Token'            => 'jarrus90\User\models\Token',
        'RegistrationForm' => 'jarrus90\User\models\RegistrationForm',
        'ResendForm'       => 'jarrus90\User\models\ResendForm',
        'LoginForm'        => 'jarrus90\User\models\LoginForm',
        'SettingsForm'     => 'jarrus90\User\models\SettingsForm',
        'RecoveryForm'     => 'jarrus90\User\models\RecoveryForm',
        'UserSearch'       => 'jarrus90\User\models\UserSearch',

        'Role'             => 'jarrus90\User\models\Role',
        'Permission'       => 'jarrus90\User\models\Permission',
        'Assignment'       => 'jarrus90\User\models\Assignment',
        'AuthItem'         => 'jarrus90\User\models\AuthItem',
    ];

    /** @inheritdoc */
    public function bootstrap($app) {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "jarrus90\\User\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
                if (in_array($name, ['User', 'Profile', 'Token', 'Account', 'Role', 'Permission', 'Assignment'])) {
                    Yii::$container->set("User\\{$name}Query", function () use ($modelName) {
                        return $modelName::find();
                    });
                }
            }
            Yii::$container->setSingleton(Finder::className(), [
                'userQuery'    => Yii::$container->get('User\UserQuery'),
                'profileQuery' => Yii::$container->get('User\ProfileQuery'),
                'tokenQuery'   => Yii::$container->get('User\TokenQuery'),
                'accountQuery' => Yii::$container->get('User\AccountQuery'),
            ]);


            if (!isset($app->get('i18n')->translations['rbac'])) {
                $app->get('i18n')->translations['rbac'] = [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            if (!isset($app->get('i18n')->translations['user'])) {
                $app->get('i18n')->translations['user'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            
            if (!$app instanceof ConsoleApplication) {
                if(!Yii::$container->has('yii\web\User')) {
                    Yii::$container->set('yii\web\User', [
                        'enableAutoLogin' => true,
                        'loginUrl' => ['/user/security/login'],
                        'identityClass' => \jarrus90\User\models\User::className(),
                    ]);
                }
                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules' => $module->urlRules,
                ];

                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }

                $configUrlRule['class'] = 'yii\web\GroupUrlRule';
                $rule = Yii::createObject($configUrlRule);

                $app->urlManager->addRules([$rule], false);

                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::className(),
                    ]);
                }
            } else {
                if(empty($app->controllerMap['migrate'])) {
                    $app->controllerMap['migrate']['class'] = 'yii\console\controllers\MigrateController';
                }
                $app->controllerMap['migrate']['migrationNamespaces'][] = 'jarrus90\User\migrations';
            }
            if (!$app->authManager instanceof DbManager) {
                $app->set('authManager', [
                    'class' => DbManager::className(),
                    'cache' => $app->cache
                ]);
            }
            Yii::$container->set('jarrus90\User\Mailer', $module->mailer);
        }
    }

}
