<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/10/13
 * Time: 14:43
 */

namespace service\tasks\order;

use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\product\Specification;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;


class orderCancelProcess extends TaskService
{
    /**
     * @inheritdoc
     */
    public function run($data)
    {
        ToolsAbstract::log($data,'order_cancel_process.log');
        if(empty($data['order_id'])){
            ToolsAbstract::log("订单id为空",'order_cancel_process.log');
            return '订单id为空';
        }

        //退库存
        $orderId = $data['order_id'];
        $orderProducts = OrderProduct::findAll(['order_id' => $orderId,'del' => 1]);

        if(empty($orderProducts)){
            ToolsAbstract::log("没有订单商品",'order_cancel_process.log');
            return "没有订单商品";
        }

        $rabbitMq = ToolsAbstract::getRabbitMq();
        $orderProductIds = [];
        foreach ($orderProducts as $productItem){
            $orderProductIds[] = $productItem->id;
            $productId = $productItem->product_id;
            $specificationId = $productItem->specification_id;
            $sql = "update `specification` set qty = qty + ".$productItem->number." where id=".$specificationId." limit 1";
            $res = Specification::getDb()->createCommand($sql)->execute();

            //发布商品更新的消息
            $data = [
                'route' => 'taskProduct.productUpdateProcess',
                'params' => [
                    'product_id' => $productId,
                ],
            ];
            $rabbitMq->publish($data);
        }

        ToolsAbstract::log($orderProductIds,'order_cancel_process.log');
        return $orderProductIds;
    }
}