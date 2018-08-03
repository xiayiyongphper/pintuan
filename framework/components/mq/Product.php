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

class Product
{

    /**
     * 更新商品信息
     * @param array $productData 商品数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishProductUpdateEvent(array $productData, $extraData = [])
    {
        ToolsAbstract::log($productData,'mq.log');
        return self::publishJson(MQAbstract::MSG_PRODUCT_UPDATE, $productData, $extraData);
    }

    /**
     * 删除商品信息
     * @param array $productData 删除商品数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishProductDeleteEvent(array $productData, $extraData = [])
    {
        ToolsAbstract::log($productData,'mq.log');
        return self::publishJson(MQAbstract::MSG_PRODUCT_DELETE, $productData, $extraData);
    }

    /**
     * 导入套餐商品子商品信息
     * @param array $productData 商品数据
     * @param array $extraData 附加数据
     * @return bool
     */
    public static function publishGroupSubProductUpdateEvent(array $productData, $extraData = [])
    {
        return self::publishJson(MQAbstract::MSG_GROUP_SUB_PRODUCT_UPDATE, $productData, $extraData);
    }


    /**
     * 发布json数据
     * @param string $routingKey 路由
     * @param array|object $productData 元数据
     * @param array $extraData 订单相关的额外信息
     * @return boolean
     */
    private static function publishJson($routingKey, $productData, $extraData)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish($routingKey, json_encode(['key' => $routingKey, 'value' => ['product' => $productData, 'extra' => $extraData]]),
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

}