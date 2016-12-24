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

use yii\rbac\Role;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RoleController extends ItemControllerAbstract {

    /** @var string */
    protected $modelClass = 'jarrus90\User\models\Role';
    protected $type = Item::TYPE_ROLE;

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
        $role = \Yii::$app->authManager->getRole($name);

        if ($role instanceof Role) {
            return $role;
        }

        throw new NotFoundHttpException;
    }

}
