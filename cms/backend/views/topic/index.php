<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TopicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '专题列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增专题', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'sort',
            'id',
            'title',
            [
                'attribute' => 'img_url',
                'format' => ['image', ['width' => '300']],
                'value' => function ($model) {
                    return $model->img_url;
                },
            ],
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    if ($model->type === 1) {
                        return '商品列表';
                    } elseif ($model->type === 2) {
                        return '拼团列表';
                    } else {
                        return '未知';
                    }
                },
            ],
            [
                'attribute' => 'products',
                'label' => '商品/拼团的数量',
                'value' => function ($model) {
                    return count(explode(',' ,$model->products));
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
