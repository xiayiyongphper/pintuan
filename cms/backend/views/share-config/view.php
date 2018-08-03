<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ShareConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '分享配置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="share-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'img_url:url',
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
        ],
    ]) ?>

</div>
