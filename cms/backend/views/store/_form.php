<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'open_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wallet')->textInput() ?>

    <?= $form->field($model, 'province')->textInput() ?>

    <?= $form->field($model, 'city')->textInput() ?>

    <?= $form->field($model, 'district')->textInput() ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detail_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'owner_user_id')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lng')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'apply_at')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'business_license_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_license_img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_front_img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'open_time_range')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contractor_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'delivery_type')->textInput() ?>

    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'commission_coefficient')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mini_program_qrcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receive_goods_qrcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wx_qrcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'owner_user_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'del')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
