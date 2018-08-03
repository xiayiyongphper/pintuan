<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivityStore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pintuan-activity-store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pintuan_activity_id')->textInput() ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'del')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
