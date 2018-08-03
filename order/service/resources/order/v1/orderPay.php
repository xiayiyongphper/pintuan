<?php
/**
 * Created by order.
 * User: Ryan Hong
 * Date: 2018/6/29
 * Time: 11:55
 */

namespace service\resources\order\v1;

use common\models\Order;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\common\BoolResponse;
use message\order\OrderPayReq;
use message\order\OrderPayRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;

/**
 * Class orderPay
 * @package service\resources\order\v1
 * 支付后回调
 */
class orderPay extends ResourceAbstract
{

    public function run($data)
    {
        $this->doInit($data);
        /** @var  OrderPayReq $request */
        $request = $this->request;
        $order = Order::findOne(['order_number' => $request->getOrderNumber()]);
        if (!$order) {
            Exception::throwException(Exception::ORDER_NOT_EXIST);
        }

        if ($order->payable_amount != $request->getPayAmount()) {
            Exception::throwException(Exception::ORDER_PAY_AMOUNT_NOT_MATCH);
        }

        if ($order->status == Order::STATUS_UNPAID) {
            $order->status = Order::STATUS_PAID;
            $order->real_amount = $request->getPayAmount();
            $order->bank_type = $request->getBankType();
            $order->settlement_total_fee = $request->getSettlementTotalFee();
            $order->transaction_id = $request->getTransactionId();
            $order->pay_at = date("Y-m-d H:i:s");
            $order->save();

            //发消息
            (ToolsAbstract::getRabbitMq())->publish([
                'route' => 'taskOrder.orderPayProcess',
                'params' => ['order_id' => $order->id]
            ]);
        }

        $orderProduct = OrderProduct::findOne(['order_id' => $order->id]);
        $pintuan_id = $orderProduct->pintuan_id;

        $response = self::response();
        $response->setOrderNumber($order->order_number);
        $response->setPintuanId($pintuan_id);
        $response->setOrderType($order->type);
        $response->setUserId($order->user_id);

        return $response;
    }

    public static function request()
    {
        return new OrderPayReq();
    }

    public static function response()
    {
        return new OrderPayRes();
    }
}