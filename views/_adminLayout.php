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
 */
?>

<?=
$this->render('@jarrus90/User/views/_alert', [
    'module' => Yii::$app->getModule('user'),
])
?>
<div class="nav-tabs-custom">
    <?= $this->render('@jarrus90/User/views/_adminMenu') ?>
    <div class="tab-content">
        <?= $content ?>
    </div>
</div>