<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Store */

$this->title = '更新小店: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
