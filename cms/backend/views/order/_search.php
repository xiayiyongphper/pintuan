<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_number') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'real_amount') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'store_id') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'refund_status') ?>

    <?php // echo $form->field($model, 'refund_at') ?>

    <?php // echo $form->field($model, 'pay_at') ?>

    <?php // echo $form->field($model, 'receive_at') ?>

    <?php // echo $form->field($model, 'receive_type') ?>

    <?php // echo $form->field($model, 'arrival_at') ?>

    <?php // echo $form->field($model, 'user_refund_reason') ?>

    <?php // echo $form->field($model, 'service_refund_reason') ?>

    <?php // echo $form->field($model, 'pintuan_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
