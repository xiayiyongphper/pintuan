<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小店列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增小店', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            //'wallet',
            //'province',
            //'city',
            //'district',
            //'area_id',
            //'address',
            //'detail_address',
            //'owner_user_id',
            //'lat',
            //'lng',
            //'store_phone',
            //'status',
            //'created_at',
            //'updated_at',
            //'apply_at',
            //'type',
            //'business_license_no',
            //'business_license_img',
            //'store_front_img',
            //'open_time_range',
            //'contractor_id',
            //'service_id',
            //'delivery_type',
            //'bank',
            //'account',
            //'account_name',
            //'commission_coefficient',
            //'mini_program_qrcode',
            //'receive_goods_qrcode',
            //'wx_qrcode',
            //'owner_user_photo',
            //'del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
