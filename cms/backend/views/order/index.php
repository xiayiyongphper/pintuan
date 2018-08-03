<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\PintuanUser;
use backend\models\Store;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单';
$this->params['breadcrumbs'][] = $this->title;
$orderexportUrl = \yii\helpers\Url::toRoute('/orderexport/index');
$financeUrl = \yii\helpers\Url::toRoute('/orderexport/finance');
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <a href="<?php echo $orderexportUrl;?>" class="btn btn-success">导出配送单据</a>
        <a href="<?php echo $financeUrl;?>" class="btn btn-success">导出财务核对单据</a>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'order_number',
            [
                'attribute'=>'订单金额(元)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return sprintf("%.2f", $model['amount'] / 100);
                }
            ],
            [
                'attribute'=>'客户账号(昵称)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $userModel = new \backend\models\PintuanUser();
                    $userInfo = $userModel::findOne($model['user_id']);
                    if ($userInfo) {
                        return $userInfo->nick_name;
                    } else {
                        return '';
                    }
                }
            ],
            [
                'attribute'=>'下单方式',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model['type'] == 1) {
                        return '普通购买';
                    } if ($model['type'] == 2) {
                        return '参与拼团';
                    } else if ($model['type'] == 3) {
                        return '发起拼团';
                    } else {
                        return '';
                    }
                }
            ],
            ['label'=>'创建时间',  'attribute' => 'create_at',  'value' => 'create_at','filter'=>
                \dosamigos\datepicker\DateRangePicker::widget([
                    'name' => "OrderSearch[created_at_from]",
                    'value' => $searchModel->created_at_from,
                    'nameTo' => "OrderSearch[created_at_to]",
                    'valueTo' => $searchModel->created_at_to,
                    'language' => 'zh-CN',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-m-dd'
                    ]
                ]),
                'headerOptions' => ['width' => '250'],
            ],
            [
                'attribute'=>'实付金额(元)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return sprintf("%.2f", $model['real_amount'] / 100);
                }
            ],
            //1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
            [
                'attribute'=>'状态',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $status  =  $model['status'];
                    if ($status == 1) {
                        return '未支付';
                    } else if ($status == 2) {
                        return '已支付';
                    } else if ($status == 3) {
                        return '已发货';
                    }else if ($status == 4) {
                        return '已到货';
                    }else if ($status == 5) {
                        return '已确认收货';
                    }else if ($status == 6) {
                        return '已取消';
                    } else {
                        return '';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
