<?php
/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;

/**
 * @var yii\web\View 					$this
 * @var jarrus90\User\models\User 		$user
 * @var jarrus90\User\models\Profile 	$profile
 */
?>

<?php $this->beginContent('@jarrus90/User/views/admin/update.php', ['user' => $user]) ?>

<?php
$form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'formConfig' => ['labelSpan' => 3]
        ]);
?>

<?= $form->field($profile, 'name') ?>
<?= $form->field($profile, 'name') ?>
<?= $form->field($profile, 'public_email') ?>
<?= $form->field($profile, 'bio')->textarea() ?>


<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
