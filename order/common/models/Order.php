<?php

namespace common\models;

use service\resources\Exception;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_number 系统生成订单号
 * @property int $user_id 用户id
 * @property int $amount 订单金额（分） 优惠前
 * @property int $discount_amount 优惠金额
 * @property int $payable_amount 应付金额（分），amount - discount_amount = payable_amount
 * @property int $real_amount 实收金额（分）（信用卡支付等造成实收金额不等于订单金额）
 * @property int $coupon_id 代金券id（如果使用了代金券）
 * @property int $type 订单类型：1-普通购买，2-参与拼团,3-发起拼团
 * @property int $pintuan_activity_id 拼团活动id
 * @property int $pintuan_id 拼团id
 * @property int $buy_chains_id 接龙活动id
 * @property int $store_id 店铺id(自提点)
 * @property int $store_name 店铺(自提点)
 * @property int $pay_type 支付方式：0-未知，1-微信支付
 * @property string $create_at 下单时间
 * @property string $update_at 更新时间
 * @property string $cancel_at 取消时间
 * @property int $status 状态：1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
 * @property int $refund_status 退款状态：1-未退款，2-已退款
 * @property string $refund_at 退款时间
 * @property string $pay_at 付款时间
 * @property string $enable_deliver_time 达到发货条件的时间
 * @property string $cancel_reason 取消原因
 * @property string $receive_at 确认收货时间
 * @property int $receive_type 确认收货方式：0-未收货，1-用户确认，2-系统自动确认
 * @property string $arrival_at 到货时间
 * @property string $user_refund_reason 用户申请退款原因
 * @property string $service_refund_reason 客服备注退款原因
 * @property int $del
 * @property string $pick_code 提货码
 * @property string $prepay_id 预生成订单id
 * @property string $bank_type 银行
 * @property string $settlement_total_fee
 * @property string $transaction_id 微信订单号
 *
 */
class Order extends \yii\db\ActiveRecord
{
    //支付方式
    const PAY_TYPE_WECHAT = 1;//微信支付
    //订单状态
    const STATUS_UNPAID = 1;//未支付 按钮：取消订单
    const STATUS_PAID = 2;//已经支付，待发货 按钮：再次购买
    const STATUS_DELIVERED = 3;//已发货，待小店收货 按钮：再次购买
    const STATUS_ARRIVED = 4;//小店已确认到货 按钮：再次购买，确认收货
    const STATUS_CONFIRMED = 5;//用户已确认到货  按钮：再次购买
    const STATUS_CANCELED = 6;//订单取消  按钮：再次购买
    //退款状态
    const REFUND_STATUS_UNREFUND = 1;//未退款
    const REFUND_STATUS_REFUND = 2;//已退款
    //确认收货方式
    const REVEIVE_TYPE_NOT_REVEIVED = 0;
    const REVEIVE_TYPE_USER_CONFIRM = 1;//用户确认
    const REVEIVE_TYPE_SYSTEM_CONFIRM = 2;//系统自动确认
    //类型
    const TYPE_NORMAL = 1;//普通购买
    const TYPE_JOIN_PINTUAN = 2;//参与拼团
    const TYPE_LAUNCH_PINTUAN = 3;//发起拼团
    const TYPE_BUY_CHAINS = 4;//发起拼团

    //删除状态
    const NOT_DELETED = 1;//未删除
    const ALREADY_DELETED = 2;//已删除

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
            [['order_number', 'user_id', 'amount', 'store_id'/*, 'create_at', 'update_at'*/], 'required'],
            [['user_id', 'amount', 'real_amount', 'type', 'store_id', 'pay_type', 'status', 'refund_status', 'receive_type'], 'integer'],
            [['create_at', 'update_at', 'pay_at', 'receive_at', 'arrival_at'], 'safe'],
            [['order_number'], 'string', 'max' => 30],
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
        ];
    }

    public function beforeSave($insert)
    {
        $date = date('Y-m-d H:i:s');
        if ($insert) {
            $this->create_at = $date;
        }
        $this->update_at = $date;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function orderCancel($user_id, $order_id, $reason)
    {
        $date = date('Y-m-d H:i:s');
        if (!$order_id || !$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        /** @var Order $order */
        $order = self::find()->where(['id' => $order_id, 'user_id' => $user_id])->andWhere(['status' => Order::STATUS_UNPAID])->one();
        if (!$order) {
            Exception::throwException(Exception::STORE_ORDER_STATUS_FAIL);
        }
        $order->status = Order::STATUS_CANCELED;
        $order->cancel_at = $date;
        $order->cancel_reason = $reason;
        $order->save();
    }

    public static function orderConfirm($user_id, $order_id)
    {
        $date = date('Y-m-d H:i:s');
        if (!$order_id || !$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        /** @var Order $order */
        $order = self::find()->where(['id' => $order_id, 'user_id' => $user_id])->andWhere(['status' => Order::STATUS_ARRIVED])->one();
        if (!$order) {
            Exception::throwException(Exception::STORE_ORDER_STATUS_FAIL);
        }
        $order->status = Order::STATUS_CONFIRMED;
        $order->receive_type = Order::REVEIVE_TYPE_USER_CONFIRM;
        $order->receive_at = $date;
        $order->save();
    }

    public static function orderDelete($user_id, $order_id)
    {
        if (!$order_id || !$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        /** @var Order $order */
        $order = self::find()->where(['id' => $order_id, 'user_id' => $user_id])->andWhere(['status' => Order::STATUS_CANCELED])->one();
        if (!$order) {
            Exception::throwException(Exception::STORE_ORDER_STATUS_FAIL);
        }
        $order->del = Order::ALREADY_DELETED;
        $order->save();
    }

    /**
     * 获取某个用户下面的某个自提点的订单数量
     * @param $user_id
     * @param $store_id
     */
    public static function getPayOrderCount($user_id, $store_id)
    {
          $where = [
              'user_id'=>$user_id,
              'store_id'=>$store_id
          ];
          $andWHere = [
              '>', 'status', self::STATUS_UNPAID
          ];
          return self::find()->where($where)->andWhere($andWHere)->count();
    }
}