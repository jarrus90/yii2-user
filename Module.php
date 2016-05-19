<?php

namespace jarrus90\User;

class Module extends \yii\base\Module {

    public $adminUrlPrefix = 'admin/users';
    public $adminUrlRules = [
        '' => 'admin/list',
        '/<id:\d+>' => 'admin/view',
        '/<id:\d+>/edit' => 'admin/edit',
        '/<id:\d+>/roles' => 'admin/roles',
    ];
    public $frontUrlPrefix = 'user';
    public $frontUrlRules = [
        'login' => 'auth/login',
    ];
    public $identityClass = 'jarrus90\User\Models\User';

}
