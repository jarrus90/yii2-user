<?php

namespace jarrus90\User;

class Module extends \yii\base\Module {

    /** @var bool Whether user can register. */
    public $enableRegistration = true;

    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = true;

    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 86400; // 6 hours

    public $_controllerMap = [
        'admin' => 'jarrus90\User\Controllers\AdminController',
        'auth' => 'jarrus90\User\Controllers\AuthController',
        'registration' => 'jarrus90\User\Controllers\RegistrationController',
        'recovery' => 'jarrus90\User\Controllers\RecoveryController',
    ];
    
    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 12;
    public $adminUrlPrefix = 'admin/users';
    public $adminUrlRules = [
        '' => 'admin/list',
        '/<id:\d+>' => 'admin/view',
        '/<id:\d+>/edit' => 'admin/edit',
        '/<id:\d+>/roles' => 'admin/roles',
    ];
    public $frontUrlPrefix = 'user';
    public $frontUrlRules = [
        '<action:(login|logout|register|resend)>' => 'auth/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot' => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>' => 'settings/<action>'
    ];
    public $identityClass = 'jarrus90\User\Models\User';

}
