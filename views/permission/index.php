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
 * @var $dataProvider array
 * @var $this         yii\web\View
 * @var $filterModel  jarrus90\User\models\Search
 */
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('rbac', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginContent('@jarrus90/User/views/_adminLayout.php') ?>

<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'pjax' => true,
    'hover' => true,
    'export' => false,
    'layout' => "{items}\n{pager}",
    'columns' => [
        [
            'attribute' => 'name',
            'header' => Yii::t('rbac', 'Name'),
            'options' => [
                'style' => 'width: 20%'
            ],
        ],
        [
            'attribute' => 'description',
            'header' => Yii::t('rbac', 'Description'),
            'options' => [
                'style' => 'width: 55%'
            ],
        ],
        [
            'attribute' => 'rule_name',
            'header' => Yii::t('rbac', 'Rule name'),
            'options' => [
                'style' => 'width: 20%'
            ],
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update} {delete}',
            'urlCreator' => function ($action, $model) {
                return Url::to(['/user/permission/' . $action, 'name' => $model['name']]);
            },
                    'options' => [
                        'style' => 'width: 5%'
                    ],
                ]
            ],
        ])
        ?>

        <?php $this->endContent() ?>