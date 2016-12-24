<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace jarrus90\User\controllers;

use yii\rbac\Permission;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;
use yii\filters\AccessControl;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class PermissionController extends ItemControllerAbstract {

    /** @var string */
    protected $modelClass = 'jarrus90\User\models\Permission';

    /** @var int */
    protected $type = Item::TYPE_PERMISSION;

    /** @inheritdoc */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin_super'],
                    ],
                ],
            ],
        ];
    }

    /** @inheritdoc */
    protected function getItem($name) {
        $role = \Yii::$app->authManager->getPermission($name);

        if ($role instanceof Permission) {
            return $role;
        }

        throw new NotFoundHttpException;
    }

}
