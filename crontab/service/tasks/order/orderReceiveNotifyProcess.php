<?php

/**
 * Created by PhpStorm.
 * 订单收货发小程序模板提醒
 * User: wjq310
 * Date: 2018/07/17
 * Time: 17:43
 */

namespace service\tasks\order;

use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\pintuan\Pintuan;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\helper\WxMiniProgramTemplateMsg;
use common\models\user\User;


class orderReceiveNotifyProcess extends TaskService
{
    /**
     * 成团后，更新 order 表中 enable_deliver_time 的时间
     * @inheritdoc
     */
    public function run($data)
    {

        /*$rabbitMq = ToolsAbstract::getRabbitMq();
        //根据order_id 发送模板消息
        $data = [
            'route' => 'taskOrder.orderReceiveNotifyProcess',
            'params' => [
                'order_id' => [1,2,3,4]  // $order_id，多个订单编号
            ],
        ];
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# data" . print_r($data, 1), 'wjqRabbitmq.log');
        $rabbitMq->publish($data);*/

        ToolsAbstract::log(var_export($data,true),'order_receive_notify_process.log');
        if(empty($data['order_id']) || !is_array($data['order_id'])){
            ToolsAbstract::log("order_id不能为空",'order_receive_notify_process.log');
            return 'order_id不为空';
        }

        $order_id_arr = $data['order_id'];

        foreach($order_id_arr as $val){
            $data = [];
            $order_id = $val;
            $data['order_id'] = $order_id;

            $order = Order::find()->select(['id','real_amount','payable_amount','pick_code as code','user_id','prepay_id'])->where(['id'=>$order_id])->asArray()->One();
            if(empty($order)){
                ToolsAbstract::log("order_id: ".$order_id." 找不到对应信息",'order_receive_notify_process.log');
                return "order_id: ".$order_id." 找不到对应信息";
            }

            if(empty($order['prepay_id'])){
                ToolsAbstract::log("order_id: ".$order_id." 对应的prepay_id 为空",'order_receive_notify_process.log');
                return "order_id: ".$order_id." 对应的prepay_id 为空";
            }
            $data['prepay_id'] = $order['prepay_id'];
            $data['product_order_amount'] = $order['payable_amount']>0?(string)round($order['payable_amount']/100,2):0; // 传元
            $data['code'] = $order['code'];

            $order_product = OrderProduct::find()->select(['name','number'])->where(['order_id'=>$order_id])->asArray()->one();
            if(empty($order_product)){
                ToolsAbstract::log("order_id: ".$order_id." 对应的商品名称name 为空",'order_receive_notify_process.log');
                return "order_id: ".$order['id']." 对应的商品名称name 为空";
            }
            $data['product_name'] = $order_product['name'];
            $data['product_number'] = $order_product['number'];

            $user_info = User::find()->select(['open_id'])->where(['id'=>$order['user_id']])->asArray()->one();
            if(empty($user_info)){
                ToolsAbstract::log("order_id: ".$order_id." 对应的open_id 为空",'order_receive_notify_process.log');
                return "order_id: ".$order_id." 对应的open_id 为空";
            }
            $data['open_id'] = $user_info['open_id'];
            ToolsAbstract::log(__CLASS__."--".__METHOD__." weixin data info: ".var_export($data,true),'wx_mini_program_template_msg.log');
            $templateMsg = new  WxMiniProgramTemplateMsg();
                   /*$data = [
                       "open_id" => "oGczD5IKuvDbYycnopd3TEiOL0iQ",
                       "order_id" => 341,
                       "prepay_id" => "wx1216061686905796eff8135c2754264530",
                       "product_number" => 1,
                       "code" => "421073",
                       "product_name" => "王家其深圳测试",
                       "product_order_amount" => 0.01,
                   ];*/
            $res = $templateMsg->sendOrderArrivalNotify($data); // 发货到货通知小程序模板
            ToolsAbstract::log(__CLASS__."--".__METHOD__." weixin return: ".var_export($res,true),'wx_mini_program_template_msg.log');
        }
        return true;


    }
}