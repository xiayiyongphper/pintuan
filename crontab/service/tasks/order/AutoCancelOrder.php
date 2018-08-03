<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/10/13
 * Time: 14:43
 */

namespace service\tasks\order;

use common\models\order\Order;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * @see MQAbstract::MSG_GROUP_SUB_PRODUCT_UPDATE
 * @package service\mq_processor\product
 */
class AutoCancelOrder extends TaskService
{
    /**
     * @inheritdoc
     */
    public function run($data)
    {
        //筛选30分钟 到 45分钟 前的订单
        $minTime = date("Y-m-d H:i:s",time() - 2700);
        $maxTime = date("Y-m-d H:i:s",time() - 1800);
        $orders = Order::find()->where(['between','create_at',$minTime,$maxTime])
            ->andWhere(['status' => Order::STATUS_UNPAID ])
            ->all();

        if(!$orders){
            return true;
        }

        $orderIds = [];
        $rabbitMq = ToolsAbstract::getRabbitMq();
        /** @var Order $order */
        foreach ($orders as $order){
            $orderIds[] = $order->id;
            $now  = date("Y-m-d H:i:s");
            $sql = "update `order` set status=".Order::STATUS_CANCELED.",cancel_at='".$now."',update_at='".$now."',cancel_reason='系统定时取消' where id=".$order->id." and status=".Order::STATUS_UNPAID." limit 1";
            ToolsAbstract::log($sql,'auto_cancel_order.log');
            $res = Order::getDb()->createCommand($sql)->execute();
            //发布取消订单的消息
            $data = [
                'route' => 'taskOrder.orderCancelProcess',
                'params' => ['order_id' => $order->id]
            ];
            $rabbitMq->publish($data);
        }

        return $orderIds;
    }
}