<?php

use yii\helpers\Html;
use backend\models\PintuanActivityStore;

/* @var $this yii\web\View */
/* @var $operation */
/* @var $model app\models\PintuanActivity */

$this->title = '更新拼团活动: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '拼团活动列表', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
// 根据商品id查出商品名称
$productInfo = \backend\models\Product::findOne(['id' => $model->product_id]);
$this->params['product_name'] = $productInfo->name;
// 查询出是否自定义自提点
$store_ids = PintuanActivityStore::find()->select('store_id')->where(['pintuan_activity_id' => $model->id])->column();
$this->params['choose_position'] = 1;
$this->params['store_id'] = '';
$this->params['store_name'] = '';
if (!empty($store_ids)) {
    $this->params['choose_position'] = 2;
    $this->params['store_id'] = implode(',', $store_ids);
    // 查询出超市的名字并显示在自提点列表中
    $storeName = \backend\models\Store::find()->select('name')->where(['id' => $store_ids])->column();
    $this->params['store_name'] = implode(',', $storeName);
}

// 人数策略反格式化
$this->params['strategy'] = json_decode($model->strategy, true);
$this->params['operation'] = isset($operation) ? $operation : '';

?>
<div class="pintuan-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'productInfo'=>$productInfo,
        'specifications'=>$specifications,
        'storeList'=>$storeList
    ]) ?>

</div>
