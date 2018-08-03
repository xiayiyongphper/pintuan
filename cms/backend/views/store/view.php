<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Store */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'auth_token',
            'open_id',
            'union_id',
            'wallet',
            'province',
            'city',
            'district',
            'area_id',
            'address',
            'detail_address',
            'owner_user_id',
            'lat',
            'lng',
            'store_phone',
            'status',
            'created_at',
            'updated_at',
            'apply_at',
            'type',
            'business_license_no',
            'business_license_img',
            'store_front_img',
            'open_time_range',
            'contractor_id',
            'service_id',
            'delivery_type',
            'bank',
            'account',
            'account_name',
            'commission_coefficient',
            'mini_program_qrcode',
            'receive_goods_qrcode',
            'wx_qrcode',
            'owner_user_photo',
            'del',
        ],
    ]) ?>

</div>
