<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分享配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="share-config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增分享配置', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    if ($model->status === 1) {
                        return '图片';
                    } elseif ($model->status === 2) {
                        return '截屏';
                    } else {
                        return '未知';
                    }
                },
            ],
            [
                'attribute' => 'position',
                'value' => function ($model) {
                    if ($model->status === 1) {
                        return '首页';
                    } elseif ($model->status === 2) {
                        return '详情';
                    } else {
                        return '未知';
                    }
                },
            ],
            [
                'attribute' => 'img_url',
                'format' => ['image', ['width' => '250']],
                'value' => function ($model) {
                    return $model->img_url;
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status === 1) {
                        return '启用';
                    } elseif ($model->status === 2) {
                        return '禁用';
                    } else {
                        return '未知';
                    }
                },
            ],
            'created_at',
            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>
</div>
