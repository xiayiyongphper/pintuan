<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Crontab */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '定时任务', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crontab-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'entity_id',
            'name',
            'route',
            'cron_format',
            'created_at',
            'updated_at',
            'sticky',
            'status',
            'from_time',
            'to_time',
            'notes',
            'params:ntext',
        ],
    ]) ?>

</div>
