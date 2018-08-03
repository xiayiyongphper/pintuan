<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PintuanUser */

$this->title = 'Create Pintuan User';
$this->params['breadcrumbs'][] = ['label' => 'Pintuan Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
