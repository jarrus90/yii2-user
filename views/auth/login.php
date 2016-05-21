<?php

use jarrus90\User\Widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var jarrus90\User\Models\LoginForm $model
 * @var jarrus90\User\Module $module
 */
$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'validateOnBlur' => false,
                    'validateOnChange' => false,
                    'validateOnType' => false,
                    'validateOnSubmit' => true
                ])
        ?>
        <?=
        $form->field($model, 'email', [
            'inputOptions' => ['placeholder' => 'enter...', 'autofocus' => 'autofocus', 'class' => 'form-control ', 'tabindex' => '1']
        ])
        ?>
        <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ($module->enablePasswordRecovery ? ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')' : '')) ?>
        <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>
        <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php if ($module->enableConfirmation): ?>
    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/resend']) ?>
    </p>
<?php endif ?>
<?php if ($module->enableRegistration): ?>
    <p class="text-center">
        <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/register']) ?>
    </p>
<?php endif ?>
<?=
Connect::widget([
    'baseAuthUrl' => ['/user/security/auth'],
])
?>