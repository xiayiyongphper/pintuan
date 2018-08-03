<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Topic */

$this->title = '修改专题: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '专题', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="topic-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'p1' => $p1,
        'p2' => $p2,
        'model' => $model,
        'selectData'=>$selectData,
    ]) ?>

</div>
