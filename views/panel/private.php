<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use jarrus90\User\widgets\Connect;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\User\Models\Profile $profile
 */
$this->title = $title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('user', 'Change email'); ?>
    </div>
    <div class="panel-body">
        <?php
        $formMail = ActiveForm::begin([
                    'id' => 'profile-mail-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class='col-md-8 input-security'>{input}</div>\n<div class='col-md-offset-4 col-md-8'>{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
        ]);
        ?>
        <?= $formMail->field($modelEmail, 'email') ?>
        <?= $formMail->field($modelEmail, 'current_password')->passwordInput() ?>
        <div class="container-fluid">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('user', 'Change password'); ?>
    </div>
    <div class="panel-body">
        <?php
        $formPassword = ActiveForm::begin([
                    'id' => 'profile-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class='col-md-8 input-security'>{input}</div>\n<div class='col-md-offset-4 col-md-8'>{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
        ]);
        ?>                
        <?= $formPassword->field($modelPassword, 'current_password')->passwordInput(); ?>
        <?= $formPassword->field($modelPassword, 'new_password')->passwordInput(); ?>     
        <?= $formPassword->field($modelPassword, 'password_repeat')->passwordInput()->label(Yii::t('user', 'Confirm new password')); ?>
        <div class="container-fluid">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('user', 'Change phone'); ?>
    </div>
    <div class="panel-body">
        <?php
        $formPhone = ActiveForm::begin([
                    'id' => 'profile-phone-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class='col-md-8 input-security'>{input}</div>\n<div class='col-md-offset-4 col-md-8'>{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
        ]);
        ?>
        <?=
        $formPhone->field($modelPhone, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => ['+9(999)999-99-99', '+99(999)999-99-99']
        ])->label(Yii::t('user', 'Current phone'))
        ?>
        <?= $formPhone->field($modelPhone, 'current_password')->passwordInput() ?>
        <div class="container-fluid">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('user', 'Social accounts'); ?>
    </div>
    <div class="panel-body">
        <?php
        $auth = Connect::begin([
                    'baseAuthUrl' => ['/user/auth/social'],
                    'accounts' => $user->accounts,
                    'autoRender' => false,
                    'popupMode' => false,
        ]);
        ?>
        <table class="table" id="authChoise">
            <?php foreach ($auth->getClients() as $key => $client): ?>
                <tr>
                    <td>
                        <div class="pull-left table_icon">
                            <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]) ?>
                        </div>
                    </td>
                    <td class="table_title">
                        <div class="social_title"><?= $client->getTitle() ?></div>
                        <?= Yii::t('user', 'AUTH_CLIENT_' . $key); ?>
                    </td>
                    <td class="table_button">
                        <?=
                        $auth->isConnected($client) ?
                                Html::a(Yii::t('user', 'Disconnect'), $auth->createClientUrl($client), [
                                    'class' => 'btn btn-danger ',
                                    'data-method' => 'post',
                                ]) :
                                Html::a(Yii::t('user', 'Connect'), $auth->createClientUrl($client), [
                                    'class' => 'btn btn-default button-ligray',
                                ])
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php Connect::end() ?>
        <div class="alert alert-info">
            <p><?= Yii::t('user', 'You can connect multiple accounts to be able to log in using them') ?>.</p>
        </div>
    </div>
</div>