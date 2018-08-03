<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Crontab */

$this->params['breadcrumbs'][] = ['label' => '定时任务', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->entity_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="crontab-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
