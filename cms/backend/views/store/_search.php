<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StoreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'auth_token') ?>

    <?= $form->field($model, 'open_id') ?>

    <?= $form->field($model, 'union_id') ?>

    <?php // echo $form->field($model, 'wallet') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'area_id') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'detail_address') ?>

    <?php // echo $form->field($model, 'owner_user_id') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'store_phone') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'apply_at') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'business_license_no') ?>

    <?php // echo $form->field($model, 'business_license_img') ?>

    <?php // echo $form->field($model, 'store_front_img') ?>

    <?php // echo $form->field($model, 'open_time_range') ?>

    <?php // echo $form->field($model, 'contractor_id') ?>

    <?php // echo $form->field($model, 'service_id') ?>

    <?php // echo $form->field($model, 'delivery_type') ?>

    <?php // echo $form->field($model, 'bank') ?>

    <?php // echo $form->field($model, 'account') ?>

    <?php // echo $form->field($model, 'account_name') ?>

    <?php // echo $form->field($model, 'commission_coefficient') ?>

    <?php // echo $form->field($model, 'mini_program_qrcode') ?>

    <?php // echo $form->field($model, 'receive_goods_qrcode') ?>

    <?php // echo $form->field($model, 'wx_qrcode') ?>

    <?php // echo $form->field($model, 'owner_user_photo') ?>

    <?php // echo $form->field($model, 'del') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
