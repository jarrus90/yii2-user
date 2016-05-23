<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = $title;
?>
<div class="col-lg-6 col-lg-offset-3">
    <?php $formRegistration = ActiveForm::begin(['id' => 'registration-form']); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center">
                <?= \Yii::t('user', 'Signup') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $formRegistration->field($registrationForm, 'email') ?>
            <?= $formRegistration->field($registrationForm, 'password')->passwordInput() ?>
            <?= $formRegistration->field($registrationForm, 'name') ?>
            <?= $formRegistration->field($registrationForm, 'surname') ?>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-4">
                    <a href="/user/login" class="btn btn-default"><?= \Yii::t('user', 'Log in') ?></a>
                </div>
                <div class="col-md-8">
                    <?= Html::submitButton(\Yii::t('user', '<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> ' . \Yii::t('user', 'Signup')), ['class' => 'btn btn-labeled btn-success', 'name' => 'login-button']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>