<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Store;
use backend\models\PintuanUser;

/* @var $this yii\web\View */
/* @var $model app\models\Pintuan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pintuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pintuan_activity_id',
//            'create_user_id',
            [
                'attribute' => 'create_user_id',
                'label' => '拼团发起人 ',
                'value' => function ($model) {
                    // 查询自提点
                    $userInfo = PintuanUser::findOne(['id' => $model->create_user_id]);
                    return $userInfo ? $userInfo->nick_name : '';
                },
            ],
            'member_num',
//            'store_id',
            [
                'attribute' => 'store_id',
                'label' => '自提点 ',
                'value' => function ($model) {
                    // 查询自提点
                    $storeInfo = Store::findOne(['id' => $model->store_id]);
                    return $storeInfo ? $storeInfo->name : '';
                },
            ],
            'create_at',
            [
                'attribute' => 'del',
                'label' => '是否有效 ',
                'value' => function ($model) {
                    return $model->del == 1 ? '是' : '否';
                },
            ],
        ],
    ]) ?>

</div>
