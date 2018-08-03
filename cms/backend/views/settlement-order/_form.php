<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SettlementOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settlement-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'settlement_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_id')->textInput() ?>

    <?= $form->field($model, 'settlement_amount')->textInput() ?>

    <?= $form->field($model, 'settlement_time')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'pay_state')->textInput() ?>

    <?= $form->field($model, 'pay_time')->textInput() ?>

    <?= $form->field($model, 'settlement_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
