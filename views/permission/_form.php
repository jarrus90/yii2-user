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
 * @var $this  yii\web\View
 * @var $model jarrus90\User\models\Role
 */
use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
?>
<div class="box box-primary">
    <?php
    $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 3]
            ])
    ?>
    <div class="box-body">
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'rule') ?>
        <?=
        $form->field($model, 'children')->widget(Select2::className(), [
            'data' => $model->getUnassignedItems(),
            'options' => [
                'id' => 'children',
                'multiple' => true
            ],
        ])
        ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>