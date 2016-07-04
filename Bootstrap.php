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

    /** @inheritdoc */
    public function bootstrap($app) {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            Yii::$container->setSingleton(UserFinder::className(), [
                'userQuery' => \jarrus90\User\models\User::find(),
                'profileQuery' => \jarrus90\User\models\Profile::find(),
                'tokenQuery' => \jarrus90\User\models\Token::find(),
                'accountQuery' => \jarrus90\User\models\Account::find(),
            ]);

            if (!$app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'jarrus90\User\Controllers';
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
            }

            if (!isset($app->get('i18n')->translations['rbac*'])) {
                $app->get('i18n')->translations['rbac*'] = [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            if (!isset($app->get('i18n')->translations['user*'])) {
                $app->get('i18n')->translations['user*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            if (!isset($app->get('i18n')->translations['user*'])) {
                $app->get('i18n')->translations['eauth*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => '@eauth/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            if (!$app->authManager instanceof DbManager) {
                $app->set('authManager', [
                    'class' => DbManager::className(),
                ]);
            }
            $app->params['yii.migrations'][] = '@jarrus90/User/migrations/';
            Yii::$container->set('jarrus90\User\Mailer', $module->mailer);
        }
    }

}
