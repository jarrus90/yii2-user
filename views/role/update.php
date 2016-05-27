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
 * @var $model jarrus90\User\models\Role
 * @var $this  yii\web\View
 */
$this->title = Yii::t('rbac', 'Update role');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginContent('@jarrus90/User/views/_adminLayout.php') ?>

<?=

$this->render('_form', [
    'model' => $model,
])
?>

<?php $this->endContent() ?>