<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_number 系统生成订单号
 * @property int $user_id 用户id
 * @property int $amount 订单金额（分）
 * @property int $real_amount 实收金额（分）（信用卡支付等造成实收金额不等于订单金额）
 * @property int $type 订单类型：1-普通订单，2-拼团订单
 * @property int $store_id 店铺id(自提点)
 * @property int $pay_type 支付方式：0-未知，1-微信支付
 * @property string $create_at 下单时间
 * @property string $update_at 更新时间
 * @property int $status 状态：1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
 * @property int $refund_status 退款状态：1-未退款，2-已退款
 * @property string $refund_at 退款时间
 * @property string $pay_at 付款时间
 * @property string $receive_at 确认收货时间
 * @property int $receive_type 确认收货方式：0-未收货，1-用户确认，2-系统自动确认
 * @property string $arrival_at 到货时间
 * @property string $user_refund_reason 用户申请退款原因
 * @property string $service_refund_reason 客服备注退款原因
 * @property int $pintuan_id 拼团活动id，type=2时才有
 * @property int $store_commission 	小店自提佣金，包含订单所有商品，单位分
 * @property int $store_commission_status 1:未转到钱包 2:已经转到钱包
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'user_id', 'amount', 'real_amount', 'store_id', 'create_at', 'update_at'], 'required'],
            [['user_id', 'amount', 'real_amount', 'type', 'store_id', 'pay_type', 'status', 'refund_status', 'receive_type', 'pintuan_id'], 'integer'],
            [['create_at', 'update_at', 'refund_at', 'pay_at', 'receive_at', 'arrival_at'], 'safe'],
            [['order_number'], 'string', 'max' => 20],
            [['user_refund_reason', 'service_refund_reason'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单id',
            'order_number' => '订单号',
            'user_id' => '用户id',
            'amount' => '订单金额',
            'real_amount' => '实付金额',
            'type' => 'Type',
            'store_id' => '小店id',
            'pay_type' => '支付方式',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status' => '订单状态',
            'refund_status' => '退款状态',
            'refund_at' => '退款时间',
            'pay_at' => '付款时间',
            'receive_at' => '确认收货时间',
            'receive_type' => '确认收货方式',
            'arrival_at' => '到货时间',
            'user_refund_reason' => '用户申请退款原因',
            'service_refund_reason' => '客服备注退款原因',
            'pintuan_id' => '拼团id',
        ];
    }

    public static function getOrder($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

    }

    public static function getStoreOrderDataProvider($store_id)
    {
        $query = Order::find()->where(['store_id' => $store_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * 获取订单单据
     * @param $wholesaler_id
     * @param $pay_start_at
     * @param $pay_end_at
     * @param $type
     */
    public function getOrderList($wholesaler_ids, $pay_start_at, $pay_end_at, $statusArr)
    {
        $where = [
            'in', 'order_product.wholesaler_id', $wholesaler_ids
        ];
        $statusWhere = [
            'in', 'order.status', $statusArr
        ];
        $andWhere = [
            'order.del'=>1
        ];

        $timeWhere = [
            'and',
            ['>=', 'order.pay_at', $pay_start_at],
            ['<=', 'order.pay_at', $pay_end_at]
        ];

        $statusWhere = [];
        $orderSelect = 'order.status,order.id,order.store_id,order.order_number,order.store_name,order.user_id,order.amount,order.payable_amount,order.real_amount,order.create_at,order.pay_at';
        $productSelect = 'order_product.item_detail,order_product.deal_price,order_product.product_id,order_product.name as productname,order_product.wholesaler_id,order_product.number,order_product.price,order_product.purchase_price';
        $addressSelect = 'order_address.name as username,order_address.phone as userphone,order_address.address as useraddress';
        $select = $orderSelect . ',' . $productSelect . ',' . $addressSelect;
        $query = self::find()->select($select)->where($where)->andWhere($statusWhere)
            ->andWhere($andWhere)->andFilterWhere($timeWhere)
            ->leftJoin('order_product', 'order_product.order_id=order.id')
            ->leftJoin('order_address', 'order_address.order_id=order.id')
            ->asArray()->all();
        return $query;
    }

    /**
     * 获取订单单据
     * @param $wholesaler_id
     * @param $pay_start_at
     * @param $pay_end_at
     * @param $timeType 1 按收货时间查询  2按支付时间查询
     */
    public function getOrderList2($wholesaler_ids, $pay_start_at, $pay_end_at, $statusArr, $timeType=1)
    {
        $where = [
            'in', 'order_product.wholesaler_id', $wholesaler_ids
        ];
        $statusWhere = [
            'in', 'order.status', $statusArr
        ];
        $andWhere = [
            'order.del'=>1
        ];

        //查询时间
        $timeTypes = [
            '1'=>'order.deliver_at',//发货时间
            '2'=>'order.pay_at',//支付时间
        ];

        $searchTime = 'order.deliver_at';
        if (isset($timeTypes[$timeType])) {
            $searchTime = $timeTypes[$timeType];
        }

        $timeWhere = [
            'and',
            ['>=', $searchTime, $pay_start_at],
            ['<=', $searchTime, $pay_end_at]
        ];

        $statusWhere = [];
        $orderSelect = 'order.status,order.id,order.store_id,order.order_number,order.store_name,order.user_id,order.amount,order.payable_amount,order.real_amount,order.create_at,order.pay_at';
        $productSelect = 'order_product.item_detail,order_product.deal_price,order_product.product_id,order_product.name as productname,order_product.wholesaler_id,order_product.number,order_product.price,order_product.purchase_price';
        $addressSelect = 'order_address.name as username,order_address.phone as userphone,order_address.address as useraddress';
        $select = $orderSelect . ',' . $productSelect . ',' . $addressSelect;
        $query = self::find()->select($select)->where($where)->andWhere($statusWhere)
            ->andWhere($andWhere)->andFilterWhere($timeWhere)
            ->leftJoin('order_product', 'order_product.order_id=order.id')
            ->leftJoin('order_address', 'order_address.order_id=order.id')
            ->asArray()->all();
        return $query;
    }
}
