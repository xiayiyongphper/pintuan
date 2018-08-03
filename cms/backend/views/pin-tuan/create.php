<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pintuan */

$this->title = 'Create Pintuan';
$this->params['breadcrumbs'][] = ['label' => 'Pintuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
