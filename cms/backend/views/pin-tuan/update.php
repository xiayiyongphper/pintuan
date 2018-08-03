<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pintuan */

$this->title = 'Update Pintuan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pintuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pintuan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
