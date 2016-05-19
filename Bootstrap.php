<?php

namespace jarrus90\User;

use Yii;
use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface {

    protected $_controllerMap = [
        'admin' => 'jarrus90\User\Controllers\AdminController',
        'auth' => 'jarrus90\User\Controllers\AuthController',
    ];
    /** @inheritdoc */
    public function bootstrap($app) {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            if ($app instanceof ConsoleApplication) {
                //$module->controllerNamespace = 'commands';
            } else {
                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => '/user/security/login',
                    'identityClass' => $module->identityClass,
                ]);
                $module->controllerMap = array_merge($this->_controllerMap, $module->controllerMap);
                $app->urlManager->addRules([new GroupUrlRule([
                    'prefix' => $module->adminUrlPrefix,
                    'rules' => $module->adminUrlRules,
                    'routePrefix' => 'user'
                ]), new GroupUrlRule([
                    'prefix' => $module->frontUrlPrefix,
                    'rules' => $module->frontUrlRules,
                    'routePrefix' => 'user'
                ])], false);
                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::className(),
                    ]);
                }
            }
            $app->params['yii.migrations'][] = '@jarrus90/User/migrations/';
            if (!isset($app->get('i18n')->translations['user*'])) {
                $app->get('i18n')->translations['user*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
        }
    }

}
