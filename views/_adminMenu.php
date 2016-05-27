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
            'label' => Yii::t('rbac', 'Users'),
            'url' => ['/user/admin/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Roles'),
            'url' => ['/user/role/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Permissions'),
            'url' => ['/user/permission/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Create'),
            'items' => [
                [
                    'label' => Yii::t('rbac', 'New user'),
                    'url' => ['/user/admin/create'],
                ],
                [
                    'label' => Yii::t('rbac', 'New role'),
                    'url' => ['/user/role/create']
                ],
                [
                    'label' => Yii::t('rbac', 'New permission'),
                    'url' => ['/user/permission/create']
                ]
            ]
        ]
    ]
])
?>