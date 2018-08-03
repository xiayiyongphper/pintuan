<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PuserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pintuan-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'auth_token') ?>

    <?= $form->field($model, 'open_id') ?>

    <?= $form->field($model, 'union_id') ?>

    <?= $form->field($model, 'session_key') ?>

    <?php // echo $form->field($model, 'nickName') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'language') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'avatarUrl') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'has_order') ?>

    <?php // echo $form->field($model, 'own_store_id') ?>

    <?php // echo $form->field($model, 'real_name') ?>

    <?php // echo $form->field($model, 'is_robot') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'del') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
