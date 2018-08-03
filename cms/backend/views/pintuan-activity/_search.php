<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pintuan-activity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'cover_picture') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'specification_id') ?>

    <?php // echo $form->field($model, 'pin_price') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'strategy') ?>

    <?php // echo $form->field($model, 'member_num') ?>

    <?php // echo $form->field($model, 'continue_pintuan') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'del') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
