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
use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\helper\WxMiniProgramTemplateMsg;
use common\models\user\User;

/**
 * @see MQAbstract::MSG_GROUP_SUB_PRODUCT_UPDATE
 * @package service\mq_processor\product
 */
class AutoDeliverOrder extends TaskService
{
    /**
     * @inheritdoc
     */
    public function run($data)
    {
        //筛选120分钟 到 150分钟 前的订单
        $minTime = date("Y-m-d H:i:s",time() - (150 * 60));
        $maxTime = date("Y-m-d H:i:s",time() - (60 * 120));
        $orders = Order::find()->where(['between','enable_deliver_time',$minTime,$maxTime])
            ->andWhere(['status' => Order::STATUS_PAID ])
            ->asArray()
            ->all();

        if(!$orders){
            return true;
        }
        ToolsAbstract::log("orders: ".var_export($orders,true),'auto_deliver_order.log');

        //没有什么要触发的事件，直接执行sql
        $sql = "update `order` set status=".Order::STATUS_DELIVERED.",deliver_at='".date("Y-m-d H:i:s")."' where enable_deliver_time BETWEEN '$minTime' and '$maxTime' and status=".Order::STATUS_PAID;
        ToolsAbstract::log($sql,'auto_deliver_order.log');
        $res_update_order = Order::getDb()->createCommand($sql)->execute();

        $data = [];
        foreach($orders as $order){
            try {
                $data['order_id'] = $order['id'];
                if(empty($order['prepay_id'])){
                    ToolsAbstract::log("order_id: ".$order['id']." 对应的prepay_id 为空",'auto_deliver_order.log');
                    continue;
                }
                $data['prepay_id'] = $order['prepay_id'];
                $data['product_order_amount'] = $order['payable_amount']>0?(string)round($order['payable_amount']/100,2):0; // 传元

                $order_product = OrderProduct::find()->select(['name'])->where(['order_id'=>$order['id']])->asArray()->one();
                if(empty($order_product)){
                    ToolsAbstract::log("order_id: ".$order['id']." 对应的商品名称name 为空",'auto_deliver_order.log');
                    continue;
                }
                $data['product_name'] = $order_product['name'];

                $user_info = User::find()->select(['open_id'])->where(['id'=>$order['user_id']])->asArray()->one();
                if(empty($user_info)){
                    ToolsAbstract::log("order_id: ".$order['id']." 对应的open_id 为空",'auto_deliver_order.log');
                    continue;
                }
                $data['open_id'] = $user_info['open_id'];
                // 发小程序模板消息
                ToolsAbstract::log(__CLASS__."--".__METHOD__." weixin data info : ".var_export($data,true),'wx_mini_program_template_msg.log');
                $templateMsg = new WxMiniProgramTemplateMsg(); // 要去掉
                $res = $templateMsg->sendOrderDeliverNotify($data);  // 订单发货消息
                ToolsAbstract::log(__CLASS__."--".__METHOD__." weixin return: ".var_export($res,true),'wx_mini_program_template_msg.log');
            } catch (\Exception $e) {
                continue;
            }
        }
        return $res_update_order;
    }
}