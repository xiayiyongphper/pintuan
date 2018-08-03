<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pintuan Activity Stores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-activity-store-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pintuan Activity Store', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pintuan_activity_id',
            'store_id',
            'create_at',
            'del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
