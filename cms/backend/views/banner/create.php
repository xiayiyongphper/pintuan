<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Banner */

$this->title = '新增Banner';
$this->params['breadcrumbs'][] = ['label' => 'Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'p1' => $p1,
        'p2' => $p2,
        'model' => $model,
    ]) ?>

</div>
