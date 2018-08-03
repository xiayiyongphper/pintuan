<?php

namespace framework\components\mq;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-3-30
 * Time: 下午12:08
 */
use framework\components\ToolsAbstract;
use framework\mq\MQAbstract;
use PhpAmqpLib\Message\AMQPMessage;

class Order
{
    /**
     * 发布订单创建事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     * @deprecated
     * @see Order::publishOrderNewEvent()
     * @author zqy
     */
    public static function publishCreateEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_CREATE, $orderData, $extraData);
    }

    /**
     * 发布订单评价事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     * @author zqy
     */
    public static function publishCommentEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_COMMENT, $orderData, $extraData);
    }

    /**
     * 发布订单取消事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishCancelEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_CANCEL, $orderData, $extraData);
    }

    /**
     * 发布同意订单取消事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishAgreeCancelEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_AGREE_CANCEL, $orderData, $extraData);
    }

    /**
     * 发布超市签收待评价事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishPendingCommentEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_PENDING_COMMENT, $orderData, $extraData);
    }

    /**
     * 发布订单返现成功事件
     *
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishRebateSuccessEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_REBATE_SUCCESS, $orderData, $extraData);
    }

    /**
     * 发布超市拒单事件
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishRejectedClosedEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_REJECTED_CLOSED, $orderData, $extraData);
    }

    /**
     * 发布商家确认订单事件
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishOrderConfirmEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_CONFIRM, $orderData, $extraData);
    }

    /**
     * 发布超市申请取消订单事件
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function publishOrderApplyCancelEvent($orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_APPLY_CANCEL, $orderData, $extraData);
    }

    /**
     * 发布新订单事件
     * @param array $orderData
     * @param array $extraData 附加数据
     * @param string $version 版本，如1.0
     * @return bool
     */
    public static function publishOrderNewEvent(array $orderData, $extraData = [], $version = '')
    {
        return self::publishJson(MQAbstract::MSG_ORDER_NEW, $orderData, $extraData, $version);
    }

    /**
     * 发布订单关闭事件
     * @param array $orderData 订单数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishOrderClosedEvent(array $orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_CLOSED, $orderData, $extraData);
    }


    /**
     * 发布订单手动退优惠券事件
     * @param array $orderData 订单数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishOrderManualReturnCouponEvent(array $orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_MANUAL_RETURN_COUPON, $orderData, $extraData);
    }

    /**
     * 发布订单手动退零钱事件
     * @param array $orderData 订单数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishOrderManualReturnChangeEvent(array $orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_MANUAL_RETURN_CHANGE, $orderData, $extraData);
    }

    /**
     * 发布手动返现及额度包转钱包事件
     * @param array $orderData
     * @param array $extraData
     * @return bool
     */
    public static function publishOrderManualRebateEvent(array $orderData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_ORDER_MANUAL_REBATE, $orderData, $extraData);
    }

    /**
     * 发布json数据
     * @param string $routingKey 路由
     * @param array|object $orderData 元数据
     * @param array $extraData 订单相关的额外信息
     * @param string $version 版本，如1.0
     * @return boolean
     */
    private static function publishJson($routingKey, $orderData, $extraData, $version = '')
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        if (!empty($version)) {
            $arr = [
                'key' => $routingKey,
                'value' => ['order' => $orderData, 'extra' => $extraData],
                'version' => $version
            ];
        } else {
            $arr = [
                'key' => $routingKey,
                'value' => ['order' => $orderData, 'extra' => $extraData],
            ];
        }
        $mq->publish($routingKey, json_encode($arr),
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback_order.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message nacked with content " . $message->body, 'nack_callback_order.log');
                $result = false;
            }
        );
        return $result;
    }

    /**
     * @param $event
     * @param $orderData
     * @param $extraData
     * @return bool
     */
    public static function forwardOrderEvent($event, $orderData, $extraData = [])
    {
        return self::publishJson($event, $orderData, $extraData);
    }
}