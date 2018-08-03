<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SettlementOrder */

$this->title = 'Create Settlement Order';
$this->params['breadcrumbs'][] = ['label' => 'Settlement Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settlement-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
