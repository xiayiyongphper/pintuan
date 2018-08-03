<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */
/* @var $orderAddress backend\models\OrderAddress */

$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['newindex']];
$this->params['breadcrumbs'][] = '订单详情';
?>
<div class="order-view">
    <div class="order-index">
        <table class="table table-bordered">
            <tbody>
            <tr class="error">
                <td colspan="2" style="background-color: #449d44;color: white">订单详情</td>
            </tr>
            <tr class="success">
                <td width="20%">订单号</td>
                <td> <?php echo $model->order_number ?></td>
            </tr>
            <!--1-普通购买，2-参与拼团，3-发起拼团-->
            <tr class="success">
                <td width="20%">订单类型</td>
                <td>
                    <?php
                    if ($model->type == 1) {
                        echo '普通购买';
                    } if ($model->type == 2) {
                        echo '参与拼团';
                    } else if ($model->type == 3) {
                        echo '发起拼团';
                    } else {
                        echo '';
                    }
                    ?>
                </td>
            </tr>
            <tr class="success">
                <td width="20%">订单状态</td>
                <td>
                    <?php
                    //状态：1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
                    if ($model->status == 1) {
                        echo '未支付';
                    } if ($model->status == 2) {
                        echo '已支付';
                    }if ($model->status == 3) {
                        echo '已发货';
                    }if ($model->status == 4) {
                        echo '已到货';
                    }if ($model->status == 5) {
                        echo '已确认收货';
                    }if ($model->status == 6) {
                        echo '已取消';
                    }
                    ?>
                </td>
            </tr>
            <tr class="success">
                <td width="20%">下单时间</td>
                <td><?php echo strtotime($model->create_at)? $model->create_at :''; ?></td>
            </tr>
            <tr class="success">
                <td width="20%">支付方式</td>
                <td>
                    <?php
                    if ($model->pay_type == 1) {
                        echo '微信支付';
                    } else {
                        echo '未知';
                    }
                    ?>
                </td>
            </tr>
            <tr class="success">
                <td width="20%">付款时间</td>
                <td><?php echo strtotime($model->pay_at)?$model->pay_at : ''; ?></td>
            </tr>


            <tr class="warning">
                <td width="20%">订单金额(元）</td>
                <td>
                    <?php
                    echo sprintf("%.2f", $model->amount / 100);
                    ?>
                </td>
            </tr>
            <tr class="warning">
                <td width="20%">优惠金额(元）</td>
                <td>
                    <?php
                    echo sprintf("%.2f", $model->discount_amount / 100);
                    ?>
                </td>
            </tr>

            <tr class="warning">
                <td width="20%">应付金额(元)</td>
                <td>
                    <?php
                    echo sprintf("%.2f", $model->payable_amount / 100);
                    ?>
                </td>
            </tr>
            <tr class="warning">
                <td width="20%">实收金额(元)</td>
                <td>
                    <?php
                    echo sprintf("%.2f", $model->real_amount / 100);
                    ?>
                </td>
            </tr>

            <tr class="success">
                <td width="20%">收货地址</td>
                <td><?php echo $orderAddress->address ?></td>
            </tr>
            <tr class="success">
                <td width="20%">自提点</td>
                <td><?php echo $store->name  . '(' . $store->detail_address . ')' ?></td>
            </tr>
            <tr class="success">
                <td width="20%">自提点电话</td>
                <td> <?php echo $store->store_phone ?></td>
            </tr>
            <tr class="success">
                <td width="20%">客户姓名</td>
                <td><?php echo $orderAddress->name ?></td>
            </tr>
            <tr class="success">
                <td width="20%">客户电话</td>
                <td> <?php echo $orderAddress->phone ?></td>
            </tr>
            <tr class="warning">
                <td width="20%">确认收货时间</td>
                <td><?php echo strtotime($model->receive_at)?$model->receive_at : ''; ?></td>
            </tr>
            <tr class="warning">
                <td width="20%">到货时间</td>
                <td><?php echo strtotime($model->arrival_at)?$model->arrival_at : ''; ?></td>
            </tr>
            <tr class="warning">
                <td width="20%">确认收货方式</td>
                <td>
                    <?php
                    //0-未收货，1-用户确认，2-系统自动确认
                    if ($model->receive_type == 0) {
                        echo '未收货';
                    } if ($model->receive_type == 1) {
                        echo '用户确认';
                    } else if ($model->receive_type == 2) {
                        echo '系统自动确认';
                    }
                    ?>
                </td>
            </tr>
            <tr class="success">
                <td width="20%">取消时间</td>
                <td><?php echo strtotime($model->cancel_at)?$model->cancel_at : ''; ?></td>
            </tr>
            <tr class="success">
                <td width="20%">取消原因</td>
                <td> <?php echo $model->cancel_reason ?></td>
            </tr>
            <tr class="success">
                <td width="20%">退款状态</td>
                <td>
                    <?php
                    //状态：1-未退款，2-已申请退款，3-已同意退款，4-已到账
                    if ($model->refund_status == 1) {
                        echo '未退款';
                    } if ($model->refund_status == 2) {
                        echo '已申请退款';
                    }if ($model->refund_status == 3) {
                        echo '已同意退款';
                    }if ($model->refund_status == 4) {
                        echo '已到账';
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr/>
    <h4><?= Html::encode('订单商品') ?></h4>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $orderProduct,
        'columns' => [
            'name',
            [
                'attribute'=>'商品编码/单位规格',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $speModel = new \backend\models\Specification();
                    $speInfo = $speModel::findOne($model->specification_id);
                    if ($speInfo) {
                        $arr = json_decode($speInfo->item_detail, true);
                        $item_detail = '';
                        foreach ($arr as $index=>$val) {
                            $item_detail .= $index .":". $val . "<br/>";
                        }
                        return '商品编码:' . $speInfo->barcode . '<br/>单位规格:<br/>' . $item_detail . '';
                    } else {
                        return '';
                    }
                }
            ],
            'number',
            [
                'attribute'=>'购买成交价格(元)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return sprintf("%.2f", $model->deal_price/ 100);
                }
            ],
            [
                'attribute'=>'合计成交金额(元)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                     return sprintf("%.2f", $model->deal_price * $model->number / 100);
                }
            ],
            [
                'attribute'=>'进价(元)',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return sprintf("%.2f", $model->purchase_price/ 100);
                }
            ],
        ],
    ]); ?>
</div>
