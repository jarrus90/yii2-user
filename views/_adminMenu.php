<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $this yii\web\View
 */
use yii\bootstrap\Nav;

?>
<?=

Nav::widget([
    'options' => [
        'class' => 'nav-tabs'
    ],
    'items' => [
        [
            'label' => Yii::t('user', 'Users'),
            'url' => ['/user/admin/index'],
            'active' => (
                !(Yii::$app->controller instanceof jarrus90\User\Controllers\RoleController) &&
                !(Yii::$app->controller instanceof jarrus90\User\Controllers\PermissionController)
            ),
            'visible' => Yii::$app->user->can('user_admin')
        ],
        [
            'label' => Yii::t('rbac', 'Roles'),
            'url' => ['/user/role/index'],
            'active' => (Yii::$app->controller instanceof jarrus90\User\Controllers\RoleController),
            'visible' => Yii::$app->user->can('admin_super')
        ],
        [
            'label' => Yii::t('rbac', 'Permissions'),
            'url' => ['/user/permission/index'],
            'active' => (Yii::$app->controller instanceof jarrus90\User\Controllers\PermissionController),
            'visible' => Yii::$app->user->can('admin_super')
        ]
    ]
])
?>