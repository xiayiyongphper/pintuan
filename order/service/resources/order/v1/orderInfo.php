<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\Order;
use common\models\OrderProduct;
use message\common\UniversalResponse;
use message\order\OrderAction;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderInfo
 * @package service\resources\order\v1
 */
class orderInfo extends ResourceAbstract
{
    public function run($data)
    {
        $this->doInit($data);
        /** @var OrderAction $request */
        $request = $this->request;
        $user_id = $request->getUserId();

        $order = null;
        if ($request->getOrderId()) {
            /** @var Order $order */
            $order = Order::find()->where(['id' => $request->getOrderId()]);
            if($user_id){
                $order->andWhere(['user_id' => $user_id]);
            }
            $order = $order->one();
        } else if ($request->getOrderNumber()) {
            /** @var Order $order */
            $order = Order::find()->where(['order_number' => $request->getOrderNumber()]);
            if($user_id){
                $order->andWhere(['user_id' => $user_id]);
            }
            $order = $order->one();
        } else {
            Exception::throwException(Exception::INVALID_PARAM);
        }


        if (!$order) {
            Exception::throwException(Exception::ORDER_NOT_EXIST);
        }

        $orderProduct = OrderProduct::findOne(['order_id' => $order->id]);
        $pintuan_id = $orderProduct->pintuan_id;

        $response = self::response();
        $response->setId($order->id);
        $response->setOrderNumber($order->order_number);
        $response->setStatus($order->status);
        $response->setAmount($order->amount);
        $response->setPintuanActivityId($order->pintuan_activity_id);
        $response->setType($order->type);
        $response->setUserId($order->user_id);
        $response->setPintuanId($pintuan_id);

        return $response;
    }

    public static function request()
    {
        return new OrderAction();
    }

    public static function response()
    {
        return new \message\common\Order();
    }

}
