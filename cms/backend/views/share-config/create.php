<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ShareConfig */

$this->title = '新增分享配置';
$this->params['breadcrumbs'][] = ['label' => '分享配置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="share-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'p1' => $p1,
        'p2' => $p2,
        'model' => $model,
    ]) ?>

</div>
