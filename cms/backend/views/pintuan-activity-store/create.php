<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PintuanActivityStore */

$this->title = 'Create Pintuan Activity Store';
$this->params['breadcrumbs'][] = ['label' => 'Pintuan Activity Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-activity-store-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
