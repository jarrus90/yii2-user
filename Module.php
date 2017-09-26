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
use yii\base\Module as BaseModule;

/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends BaseModule {

    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;

    /** @var bool Whether to show flash messages. */
    public $enableLastLogin = true;

    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = true;

    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;

    /** @var bool Whether to remove password field from registration form. */
    public $enableGeneratingPassword = false;

    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;

    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;

    /** @var bool Whether to enable gravatar. */
    public $avatarGravatarEnable = true;

    /** @var string Default gravatar. */
    public $avatarGravatarDefault = 'mm';

    /** @var string Default gravatar rating. */
    public $avatarGravatarRating = 'g';
    
    /** @var string Default gravatar rating. */
    public $avatarGravatarDefaultSize = 200;

    /** @var string Default avatar save path. */
    public $avatarPathDefault;

    /** @var string Default avatar view url. */
    public $avatarUrlDefault;

    /** @var int Email changing strategy. */
    public $emailChangeStrategy = self::STRATEGY_SECURE;

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 6 hours

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var string The Administrator permission name. */
    public $adminPermission;

    /** @var array Mailer configuration */
    public $mailer = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array Model map */
    public $modelMap = [];

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>' => 'profile/show',
        '<action:(login|logout)>' => 'security/<action>',
        '<action:(register|resend)>' => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot' => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>' => 'settings/<action>'
    ];
    public $filesUploadUrl = '@web/uploads/blog';
    public $filesUploadDir = '@webroot/uploads/blog';
    public $useCommonStorage = false;

    public function init() {
        parent::init();
        if (!$this->get('storage', false)) {
            if ($this->useCommonStorage && ($storage = Yii::$app->get('storage', false))) {
                $this->set('storage', $storage);
            } else {
                $this->set('storage', [
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'path' => $this->filesUploadDir
                ]);
            }
        }
        if (($user = Yii::$app->get('user', false))) {
            $user->on(Yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
                /** @var $event \yii\web\UserEvent */
                $event->identity->updateAttributes(['last_login' => time()]);
            });
        }
    }

    public function getAdminMenu() {
        return [
            'user' => [
                'label' => Yii::t('user', 'Users'),
                'icon' => '<i class="fa fa-users"></i>',
                'position' => 2,
                'items' => [
                    [
                        'label' => Yii::t('user', 'Users'),
                        'url' => ['/user/admin/index'],
                        'visible' => Yii::$app->user->can('user_admin'),
                        'active' => (Yii::$app->controller->id == 'admin' && Yii::$app->controller->module->id == 'user')
                    ],
                    [
                        'label' => Yii::t('rbac', 'Roles'),
                        'url' => ['/user/role/index'],
                        'visible' => Yii::$app->user->can('admin_super'),
                        'active' => (Yii::$app->controller->id == 'role' && Yii::$app->controller->module->id == 'user')
                    ],
                    [
                        'label' => Yii::t('rbac', 'Permissions'),
                        'url' => ['/user/permission/index'],
                        'visible' => Yii::$app->user->can('admin_super'),
                        'active' => (Yii::$app->controller->id == 'permission' && Yii::$app->controller->module->id == 'user')
                    ],
                ]
            ],
            'login' => [
                'icon' => '<i class="fa fa-sign-out"></i>',
                'label' => Yii::t('user', 'Sign in'),
                'visible' => Yii::$app->user->isGuest,
                'position' => 100,
                'url' => ['/user/security/login']
            ],
            'logout' => [
                'icon' => '<i class="fa fa-sign-out"></i>',
                'label' => Yii::t('user', 'Sign out'),
                'visible' => !Yii::$app->user->isGuest,
                'position' => 100,
                'url' => ['/user/security/logout']
            ]
        ];
    }
    
}
