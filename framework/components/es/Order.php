<?php
namespace framework\components\es;

use framework\components\ToolsAbstract;
use framework\mq\MQAbstract;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-6
 * Time: 下午12:15
 */
class Order extends EsAbstract
{
    protected $index = '.order';
    protected $type = 'order';
    protected static $instance;
    protected $properties_mapping = [
        'entity_id' => [
            'type' => 'integer',//1
        ],
        'increment_id' => [
            'type' => 'string',//2
        ],
        'wholesaler_id' => [
            'type' => 'integer',//3
        ],
        'wholesaler_name' => [
            'type' => 'string',//4
        ],
        'state' => [
            'type' => 'string',//5
        ],
        'status' => [
            'type' => 'string',//6
        ],
        'applied_rule_ids' => [
            'type' => 'string',//7
        ],
        'payment_method' => [
            'type' => 'string',//8
        ],
        'customer_id' => [
            'type' => 'integer',//9
        ],
        'store_name' => [
            'type' => 'string',//10
        ],
        'phone' => [
            'type' => 'string',//11
        ],
        'delivery_method' => [
            'type' => 'integer',//12
        ],
        'province' => [
            'type' => 'integer',//13
        ],
        'city' => [
            'type' => 'integer',//14
        ],
        'district' => [
            'type' => 'integer',//15
        ],
        'area_id' => [
            'type' => 'integer',//16
        ],
        'remote_ip' => [
            'type' => 'string',//17
        ],
        'hold_before_status' => [
            'type' => 'string',//18
        ],
        'hold_before_state' => [
            'type' => 'string',//19
        ],
        'customer_note' => [
            'type' => 'string',//20
        ],
        'balance' => [
            'type' => 'float',//21
        ],
        'rebates' => [
            'type' => 'float',//22
        ],
        'commission' => [
            'type' => 'float',//23
        ],
        'promotions' => [
            'type' => 'string',//24
        ],
        'merchant_remarks' => [
            'type' => 'string',//25
        ],
        'total_qty_ordered' => [
            'type' => 'integer',//26
        ],
        'total_due' => [
            'type' => 'float',//27
        ],
        'total_paid' => [
            'type' => 'float',//28
        ],
        'discount_amount' => [
            'type' => 'float',//29
        ],
        'total_item_count' => [
            'type' => 'integer',//30
        ],
        'coupon_discount_amount' => [
            'type' => 'float',//31
        ],
        'shipping_amount' => [
            'type' => 'float',//32
        ],
        'subtotal' => [
            'type' => 'float',//33
        ],
        'grand_total' => [
            'type' => 'float',//34
        ],
        'pay_time' => [
            'type' => 'date',//35
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'complete_at' => [
            'type' => 'date',//36
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'expire_time' => [
            'type' => 'date',//37
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'created_at' => [
            'type' => 'date',//38
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'remind_count' => [
            'type' => 'integer',//39
        ],
        'remind_at' => [
            'type' => 'date',//40
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'updated_at' => [
            'type' => 'date',//41
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'receipt' => [
            'type' => 'integer',//42
        ],
        'receipt_total' => [
            'type' => 'float',//43
        ],
        'rebates_lelai' => [
            'type' => 'float',//44
        ],
        'source' => [
            'type' => 'integer',//45
        ],
        'contractor_id' => [
            'type' => 'integer',//46
        ],
        'contractor' => [
            'type' => 'string',//47
        ],
        'store_label1' => [
            'type' => 'integer',//48
        ],
        'storekeeper' => [
            'type' => 'string',//49
        ],
        'additional_info' => [
            'type' => 'string',//50
        ],
        'coupon_id' => [
            'type' => 'integer',//51
        ],
        'is_first_order' => [
            'type' => 'integer',//52
        ],
        'rebates_wholesaler' => [
            'type' => 'float',//53
        ],
        'subsidies_lelai' => [
            'type' => 'float',//54
        ],
        'subsidies_wholesaler' => [
            'type' => 'float',//55
        ],
        'timestamp' => [
            'type' => 'date',
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
        'address' => [
            'properties' => [
                'entity_id' => [
                    'type' => 'integer',//1
                ],
                'order_id' => [
                    'type' => 'integer',//2
                ],
                'name' => [
                    'type' => 'string',//3
                ],
                'phone' => [
                    'type' => 'string',//4
                ],
                'address' => [
                    'type' => 'string',//5
                ]
            ]
        ],
        'items' => [
            'type' => 'nested',
            'properties' => [
                'item_id' => [
                    'type' => 'integer',//1
                ],
                'order_id' => [
                    'type' => 'integer',//2
                ],
                'wholesaler_id' => [
                    'type' => 'integer',//3
                ],
                'created_at' => [
                    'type' => 'date',//4
                    "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
                ],
                'updated_at' => [
                    'type' => 'date',//5
                    "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
                ],
                'product_id' => [
                    'type' => 'integer',//6
                ],
                'sku' => [
                    'type' => 'string',//7
                ],
                'first_category_id' => [
                    'type' => 'integer',//8
                ],
                'second_category_id' => [
                    'type' => 'integer',//9
                ],
                'third_category_id' => [
                    'type' => 'integer',//10
                ],
                'product_type' => [
                    'type' => 'string',//11
                ],
                'product_options' => [
                    'type' => 'string',//12
                ],
                'tags' => [
                    'type' => 'integer',//13
                ],
                'weight' => [
                    'type' => 'float',//14
                ],
                'barcode' => [
                    'type' => 'string',//15
                ],
                'name' => [
                    'type' => 'string',//16
                ],
                'brand' => [
                    'type' => 'string',//17
                ],
                'image' => [
                    'type' => 'string',//18
                ],
                'specification' => [
                    'type' => 'string',//19
                ],
                'qty' => [
                    'type' => 'integer',//20
                ],
                'price' => [
                    'type' => 'float',//21
                ],
                'original_price' => [
                    'type' => 'float',//22
                ],
                'row_total' => [
                    'type' => 'float',//23
                ],
                'rebates' => [
                    'type' => 'float',//24
                ],
                'is_calculate_lelai_rebates' => [
                    'type' => 'integer',//25
                ],
                'rebates_calculate' => [
                    'type' => 'float',//26
                ],
                'commission_percent' => [
                    'type' => 'float',//27
                ],
                'commission' => [
                    'type' => 'float',//28
                ],
                'receipt' => [
                    'type' => 'integer',//29
                ],
                'subsidies_wholesaler' => [
                    'type' => 'float',//30
                ],
                'subsidies_lelai' => [
                    'type' => 'float',//31
                ],
                'rebates_calculate_lelai' => [
                    'type' => 'float',//32
                ],
                'rebates_lelai' => [
                    'type' => 'float',//33
                ],
                'origin' => [
                    'type' => 'string',//34
                ],
                'production_date' => [
                    'type' => 'date',//35
                    "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
                ],
                'promotion_text' => [
                    'type' => 'string',//36
                ],
                'promotion_title' => [
                    'type' => 'string',//37
                ],
            ]
        ],
        'history' => [
            'type' => 'nested',
            'properties' => [
                'entity_id' => [
                    'type' => 'integer',//1
                ],
                'parent_id' => [
                    'type' => 'integer',//2
                ],
                'operator' => [
                    'type' => 'string',//3
                ],
                'is_customer_notified' => [
                    'type' => 'integer',//4
                ],
                'is_visible_on_front' => [
                    'type' => 'integer',//5
                ],
                'is_visible_to_customer' => [
                    'type' => 'integer',//6
                ],
                'is_visible_to_seller' => [
                    'type' => 'integer',//7
                ],
                'comment' => [
                    'type' => 'string',//8
                ],
                'status' => [
                    'type' => 'string',//9
                ],
                'created_at' => [
                    'type' => 'date',//10
                    "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
                ]
            ]
        ]
    ];

    /**
     * @return $this
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function update($orderId, $data)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish(MQAbstract::MSG_ORDER_UPDATE, json_encode(['key' => MQAbstract::MSG_ORDER_UPDATE, '__id__' => $orderId, 'index' => $this->index, 'type' => $this->type, 'body' => $data]),
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

    public function create($orderId, $data)
    {
        $mq = ToolsAbstract::getMQ();
        $result = false;
        $mq->publish(MQAbstract::MSG_ORDER_CREATE, json_encode(['key' => MQAbstract::MSG_ORDER_CREATE, '__id__' => $orderId, 'index' => $this->index, 'type' => $this->type, 'body' => $data]),
            function (AMQPMessage $message) use ($result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'ack_callback.log');
                $result = true;
            },
            function (AMQPMessage $message) use ($result) {
                ToolsAbstract::log("Message acked with content " . $message->body, 'nack_callback.log');
                $result = false;
            }
        );
        return $result;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getType()
    {
        return $this->type;
    }
}
