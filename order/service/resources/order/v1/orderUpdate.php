<?php
/**
 * Created by order.
 * User: Ryan Hong
 * Date: 2018/6/29
 * Time: 11:55
 */

namespace service\resources\order\v1;

use common\models\Order;
use message\order\OrderUpdateRequest;
use message\order\OrderUpdateResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class orderPay
 * @package service\resources\order\v1
 */
class orderUpdate extends ResourceAbstract
{

    public function run($data)
    {
        $this->doInit($data);
        /** @var  OrderUpdateRequest $request */
        $request = $this->request;
        $order = Order::findOne(['order_number' => $request->getOrderNumber()]);
//        Tools::log($request->toArray(), 'orderUpdate.log');
        if (!$order) {
            Exception::throwException(Exception::ORDER_NOT_EXIST);
        }

        if ($request->getPrepayId()) {
            $order->prepay_id = $request->getPrepayId();
        }
        $order->save();
        $response = self::response();
        $response->setOrderNumber($order->order_number);
        $response->setUserId($order->user_id);

        return $response;
    }

    public static function request()
    {
        return new OrderUpdateRequest();
    }

    public static function response()
    {
        return new OrderUpdateResponse();
    }
}