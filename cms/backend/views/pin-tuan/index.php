<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PintuanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pintuans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pintuan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pintuan_activity_id',
            'create_user_id',
            'member_num',
            'create_at',
            //'del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
