<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pintuan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pintuan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pintuan_activity_id')->label('拼团活动id')->textInput() ?>

    <?= $form->field($model, 'create_user_id')->label('拼团发起人id')->textInput() ?>

    <?= $form->field($model, 'member_num')->label('拼团人数')->textInput() ?>

    <?= $form->field($model, 'store_id')->label('自提点id')->textInput() ?>

    <?= $form->field($model, 'create_at')->label('创建时间')->textInput() ?>

    <?= $form->field($model, 'del')->label('是否有效')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
