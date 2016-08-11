<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\web\View;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use jarrus90\User\models\User;

/**
 * @var View 	$this
 * @var User 	$user
 * @var string 	$content
 */
$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginContent('@jarrus90/User/views/_adminLayout.php') ?>

<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <?=
            Nav::widget([
                'options' => [
                    'class' => 'nav-pills nav-stacked',
                ],
                'items' => [
                    [
                        'label' => Yii::t('user', 'Account details'),
                        'url' => Url::toRoute(['/user/admin/update', 'id' => $user->id])
                    ],
                    [
                        'label' => Yii::t('user', 'Profile details'),
                        'url' => Url::toRoute(['/user/admin/update-profile', 'id' => $user->id])
                    ],
                    [
                        'label' => Yii::t('user', 'Information'),
                        'url' => Url::toRoute(['/user/admin/info', 'id' => $user->id])
                    ],
                    [
                        'label' => Yii::t('user', 'Assignments'),
                        'url' => Url::toRoute(['/user/admin/assignments', 'id' => $user->id]),
                    ],
                    [
                        'label' => Yii::t('user-purse', 'Purse'),
                        'url' => Url::toRoute(['/user-purse/admin/user', 'id' => $user->id]),
                        'visible' => ISSET(Yii::$app->extensions['jarrus90/yii2-user-purse']),
                        'active' => Yii::$app->controller instanceof jarrus90\UserPurse\Controllers\AdminController
                    ],
                    [
                        'label' => Yii::t('user', 'Confirm'),
                        'url' => Url::toRoute(['/user/admin/confirm', 'id' => $user->id]),
                        'visible' => !$user->isConfirmed,
                        'linkOptions' => [
                            'class' => 'text-success',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                        ],
                    ],
                    [
                        'label' => Yii::t('user', 'Block'),
                        'url' => Url::toRoute(['/user/admin/block', 'id' => $user->id]),
                        'visible' => !$user->isBlocked,
                        'linkOptions' => [
                            'class' => 'text-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                        ],
                    ],
                    [
                        'label' => Yii::t('user', 'Unblock'),
                        'url' => Url::toRoute(['/user/admin/block', 'id' => $user->id]),
                        'visible' => $user->isBlocked,
                        'linkOptions' => [
                            'class' => 'text-success',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                        ],
                    ],
                    [
                        'label' => Yii::t('user', 'Delete'),
                        'url' => Url::toRoute(['/user/admin/delete', 'id' => $user->id]),
                        'linkOptions' => [
                            'class' => 'text-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                        ],
                    ],
                ],
            ])
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-body">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent() ?>