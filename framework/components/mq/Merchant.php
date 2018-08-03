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

class Merchant
{
    /**
     * 发布秒杀活动推送事件
     *
     * @param $pushData
     * @param $extraData
     * @return bool
     */
    public static function publishPushEvent($pushData)
    {
        return self::publishJson(MQAbstract::MSG_MERCHANT_SECKILL_PUSH, $pushData);
    }

    /**
     * 发布进入首页事件
     *
     * @param array $pushData
     * @return bool
     */
    public static function publishEnterHomePageEvent($pushData)
    {
        return self::publishJson(MQAbstract::MSG_MERCHANT_HOMEPAGE, $pushData);
    }

    /**
     * 发布编辑店铺事件
     *
     * @param array $pushData
     * @return bool
     */
    public static function publishUpdateStoreEvent($pushData)
    {
        return self::publishJson(MQAbstract::MSG_MERCHANT_UPDATE_STORE, $pushData);
    }

    /**
     * 发布json数据
     * @param string $routingKey 路由
     * @param array|object $pushData 元数据
     * @param array $extraData 订单相关的额外信息
     * @return boolean
     */
    private static function publishJson($routingKey, $pushData)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish($routingKey, json_encode(['key' => $routingKey, 'value' => $pushData]),
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback_merchant.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message nacked with content " . $message->body, 'nack_callback_merchant.log');
                $result = false;
            }
        );
        return $result;
    }

}