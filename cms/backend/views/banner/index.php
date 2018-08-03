<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <img src="" width="200" height="200" alt="">
    <p>
        <?= Html::a('新增Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
            'sort',
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
                        return '商品详情';
                    } elseif ($model->type === 2) {
                        return '拼团详情';
                    } elseif ($model->type === 3) {
                        return '专题';
                    } else {
                        return '未知';
                    }
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
