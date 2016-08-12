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

/*
 * @var yii\web\View $this
 * @var jarrus90\User\models\User $user
 */
?>

<?php $this->beginContent('@jarrus90/User/views/admin/update.php', ['user' => $user]) ?>
<div class="box box-primary">
    <?php
    $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'formConfig' => ['labelSpan' => 3]
    ]);
    ?>
    <div class="box-body">
        <?= $this->render('_user', ['form' => $form, 'user' => $user]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endContent() ?>
