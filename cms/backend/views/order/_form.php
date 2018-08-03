<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'real_amount')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'refund_status')->textInput() ?>

    <?= $form->field($model, 'refund_at')->textInput() ?>

    <?= $form->field($model, 'pay_at')->textInput() ?>

    <?= $form->field($model, 'receive_at')->textInput() ?>

    <?= $form->field($model, 'receive_type')->textInput() ?>

    <?= $form->field($model, 'arrival_at')->textInput() ?>

    <?= $form->field($model, 'user_refund_reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'service_refund_reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pintuan_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
