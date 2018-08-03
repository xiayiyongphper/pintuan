<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ShareConfig */

$this->title = '修改分享配置: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '分享配置', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="share-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'p1' => $p1,
        'p2' => $p2,
        'model' => $model,
    ]) ?>

</div>
