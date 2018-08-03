<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Topic */

$this->title = '新增专题';
$this->params['breadcrumbs'][] = ['label' => '专题', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'p1' => $p1,
        'p2' => $p2,
        'model' => $model,
    ]) ?>

</div>
