<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SettlementOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Settlement Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settlement-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'settlement_num',
            'business_id',
            'business_name',
            'bank',
            'account',
            'account_name',
            'settlement_amount',
            'settlement_time',
            'created_at',
            'updated_at',
            'pay_state',
            'pay_time',
            'settlement_type',
        ],
    ]) ?>

</div>
