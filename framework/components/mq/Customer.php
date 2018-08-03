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

class Customer
{
    /**
     * 发布用户登录事件
     *
     * @param array $data
     * @return bool
     */
    public static function publishLoginEvent($data)
    {
        return self::publishJson(MQAbstract::MSG_CUSTOMER_LOGIN, $data);
    }

    public static function publishUpdateEvent($data)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish(MQAbstract::MSG_CUSTOMER_UPDATE, json_encode(['key' => MQAbstract::MSG_CUSTOMER_UPDATE, 'value' => $data]),
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'nack_callback.log');
                $result = false;
            }
        );
        return $result;
    }

    /**
     *
     * 发布新建用户事件到RabbitMQ
     * @param array $data
     * @return boolean
     *
     */
    public static function publishCreateEvent($data)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish(MQAbstract::MSG_CUSTOMER_CREATE, json_encode(['key' => MQAbstract::MSG_CUSTOMER_CREATE, 'value' => $data]),
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback_customer_approved.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'nack_callback_customer_approved.log');
                $result = false;
            }
        );
        return $result;
    }

    /**
     *
     * 发布用户审核通过事件到RabbitMQ
     * @param array $data
     * @return boolean
     *
     */
    public static function publishApprovedEvent($data)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish(MQAbstract::MSG_CUSTOMER_APPROVED, json_encode(['key' => MQAbstract::MSG_CUSTOMER_APPROVED, 'value' => $data]),
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback_customer_approved.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'nack_callback_customer_approved.log');
                $result = false;
            }
        );
        return $result;
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
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback_seckill.log');
                $result = true;
            },
            function (AMQPMessage $message) use (&$result) {
                ToolsAbstract::log("Message nacked with content " . $message->body, 'nack_callback_seckill.log');
                $result = false;
            }
        );
        return $result;
    }
}