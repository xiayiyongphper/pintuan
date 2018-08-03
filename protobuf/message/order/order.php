<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * order message
 */
class order extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const order_number = 2;
    const user_id = 3;
    const amount = 4;
    const payable_amount = 5;
    const real_amount = 6;
    const coupon_id = 7;
    const type = 8;
    const pintuan_activity_id = 9;
    const store_id = 10;
    const pay_type = 11;
    const create_at = 12;
    const update_at = 13;
    const cancel_at = 14;
    const status = 15;
    const refund_status = 16;
    const refund_at = 17;
    const pay_at = 18;
    const cancel_reason = 19;
    const receive_at = 20;
    const receive_type = 21;
    const arrival_at = 22;
    const user_refund_reason = 23;
    const service_refund_reason = 24;
    const del = 25;
    const pick_code = 26;
    const name = 27;
    const phone = 28;
    const commission = 29;
    const store_name = 30;
    const product_name = 31;
    const product_number = 32;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::order_number => array(
            'name' => 'order_number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::amount => array(
            'name' => 'amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::payable_amount => array(
            'name' => 'payable_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::real_amount => array(
            'name' => 'real_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::coupon_id => array(
            'name' => 'coupon_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pay_type => array(
            'name' => 'pay_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::update_at => array(
            'name' => 'update_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::cancel_at => array(
            'name' => 'cancel_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::refund_status => array(
            'name' => 'refund_status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::refund_at => array(
            'name' => 'refund_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pay_at => array(
            'name' => 'pay_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::cancel_reason => array(
            'name' => 'cancel_reason',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::receive_at => array(
            'name' => 'receive_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::receive_type => array(
            'name' => 'receive_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_at => array(
            'name' => 'arrival_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::user_refund_reason => array(
            'name' => 'user_refund_reason',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::service_refund_reason => array(
            'name' => 'service_refund_reason',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::del => array(
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pick_code => array(
            'name' => 'pick_code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::name => array(
            'name' => 'name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::phone => array(
            'name' => 'phone',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::commission => array(
            'name' => 'commission',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_FLOAT,
        ),
        self::store_name => array(
            'name' => 'store_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_name => array(
            'name' => 'product_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_number => array(
            'name' => 'product_number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::id] = null;
        $this->values[self::order_number] = null;
        $this->values[self::user_id] = null;
        $this->values[self::amount] = null;
        $this->values[self::payable_amount] = null;
        $this->values[self::real_amount] = null;
        $this->values[self::coupon_id] = null;
        $this->values[self::type] = null;
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::store_id] = null;
        $this->values[self::pay_type] = null;
        $this->values[self::create_at] = null;
        $this->values[self::update_at] = null;
        $this->values[self::cancel_at] = null;
        $this->values[self::status] = null;
        $this->values[self::refund_status] = null;
        $this->values[self::refund_at] = null;
        $this->values[self::pay_at] = null;
        $this->values[self::cancel_reason] = null;
        $this->values[self::receive_at] = null;
        $this->values[self::receive_type] = null;
        $this->values[self::arrival_at] = null;
        $this->values[self::user_refund_reason] = null;
        $this->values[self::service_refund_reason] = null;
        $this->values[self::del] = null;
        $this->values[self::pick_code] = null;
        $this->values[self::name] = null;
        $this->values[self::phone] = null;
        $this->values[self::commission] = null;
        $this->values[self::store_name] = null;
        $this->values[self::product_name] = null;
        $this->values[self::product_number] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::id, $value);
    }

    /**
     * Returns value of 'id' property
     *
     * @return integer
     */
    public function getId()
    {
        $value = $this->get(self::id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'order_number' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOrderNumber($value)
    {
        return $this->set(self::order_number, $value);
    }

    /**
     * Returns value of 'order_number' property
     *
     * @return string
     */
    public function getOrderNumber()
    {
        $value = $this->get(self::order_number);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::user_id, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return integer
     */
    public function getUserId()
    {
        $value = $this->get(self::user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAmount($value)
    {
        return $this->set(self::amount, $value);
    }

    /**
     * Returns value of 'amount' property
     *
     * @return string
     */
    public function getAmount()
    {
        $value = $this->get(self::amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'payable_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPayableAmount($value)
    {
        return $this->set(self::payable_amount, $value);
    }

    /**
     * Returns value of 'payable_amount' property
     *
     * @return string
     */
    public function getPayableAmount()
    {
        $value = $this->get(self::payable_amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'real_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRealAmount($value)
    {
        return $this->set(self::real_amount, $value);
    }

    /**
     * Returns value of 'real_amount' property
     *
     * @return string
     */
    public function getRealAmount()
    {
        $value = $this->get(self::real_amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'coupon_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCouponId($value)
    {
        return $this->set(self::coupon_id, $value);
    }

    /**
     * Returns value of 'coupon_id' property
     *
     * @return integer
     */
    public function getCouponId()
    {
        $value = $this->get(self::coupon_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pintuan_activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanActivityId($value)
    {
        return $this->set(self::pintuan_activity_id, $value);
    }

    /**
     * Returns value of 'pintuan_activity_id' property
     *
     * @return integer
     */
    public function getPintuanActivityId()
    {
        $value = $this->get(self::pintuan_activity_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreId($value)
    {
        return $this->set(self::store_id, $value);
    }

    /**
     * Returns value of 'store_id' property
     *
     * @return integer
     */
    public function getStoreId()
    {
        $value = $this->get(self::store_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pay_type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPayType($value)
    {
        return $this->set(self::pay_type, $value);
    }

    /**
     * Returns value of 'pay_type' property
     *
     * @return integer
     */
    public function getPayType()
    {
        $value = $this->get(self::pay_type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'create_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCreateAt($value)
    {
        return $this->set(self::create_at, $value);
    }

    /**
     * Returns value of 'create_at' property
     *
     * @return string
     */
    public function getCreateAt()
    {
        $value = $this->get(self::create_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'update_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUpdateAt($value)
    {
        return $this->set(self::update_at, $value);
    }

    /**
     * Returns value of 'update_at' property
     *
     * @return string
     */
    public function getUpdateAt()
    {
        $value = $this->get(self::update_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'cancel_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCancelAt($value)
    {
        return $this->set(self::cancel_at, $value);
    }

    /**
     * Returns value of 'cancel_at' property
     *
     * @return string
     */
    public function getCancelAt()
    {
        $value = $this->get(self::cancel_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStatus($value)
    {
        return $this->set(self::status, $value);
    }

    /**
     * Returns value of 'status' property
     *
     * @return integer
     */
    public function getStatus()
    {
        $value = $this->get(self::status);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'refund_status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setRefundStatus($value)
    {
        return $this->set(self::refund_status, $value);
    }

    /**
     * Returns value of 'refund_status' property
     *
     * @return integer
     */
    public function getRefundStatus()
    {
        $value = $this->get(self::refund_status);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'refund_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRefundAt($value)
    {
        return $this->set(self::refund_at, $value);
    }

    /**
     * Returns value of 'refund_at' property
     *
     * @return string
     */
    public function getRefundAt()
    {
        $value = $this->get(self::refund_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'pay_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPayAt($value)
    {
        return $this->set(self::pay_at, $value);
    }

    /**
     * Returns value of 'pay_at' property
     *
     * @return string
     */
    public function getPayAt()
    {
        $value = $this->get(self::pay_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'cancel_reason' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCancelReason($value)
    {
        return $this->set(self::cancel_reason, $value);
    }

    /**
     * Returns value of 'cancel_reason' property
     *
     * @return string
     */
    public function getCancelReason()
    {
        $value = $this->get(self::cancel_reason);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'receive_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setReceiveAt($value)
    {
        return $this->set(self::receive_at, $value);
    }

    /**
     * Returns value of 'receive_at' property
     *
     * @return string
     */
    public function getReceiveAt()
    {
        $value = $this->get(self::receive_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'receive_type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setReceiveType($value)
    {
        return $this->set(self::receive_type, $value);
    }

    /**
     * Returns value of 'receive_type' property
     *
     * @return integer
     */
    public function getReceiveType()
    {
        $value = $this->get(self::receive_type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'arrival_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setArrivalAt($value)
    {
        return $this->set(self::arrival_at, $value);
    }

    /**
     * Returns value of 'arrival_at' property
     *
     * @return string
     */
    public function getArrivalAt()
    {
        $value = $this->get(self::arrival_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'user_refund_reason' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUserRefundReason($value)
    {
        return $this->set(self::user_refund_reason, $value);
    }

    /**
     * Returns value of 'user_refund_reason' property
     *
     * @return string
     */
    public function getUserRefundReason()
    {
        $value = $this->get(self::user_refund_reason);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'service_refund_reason' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setServiceRefundReason($value)
    {
        return $this->set(self::service_refund_reason, $value);
    }

    /**
     * Returns value of 'service_refund_reason' property
     *
     * @return string
     */
    public function getServiceRefundReason()
    {
        $value = $this->get(self::service_refund_reason);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'del' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDel($value)
    {
        return $this->set(self::del, $value);
    }

    /**
     * Returns value of 'del' property
     *
     * @return integer
     */
    public function getDel()
    {
        $value = $this->get(self::del);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pick_code' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPickCode($value)
    {
        return $this->set(self::pick_code, $value);
    }

    /**
     * Returns value of 'pick_code' property
     *
     * @return string
     */
    public function getPickCode()
    {
        $value = $this->get(self::pick_code);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::name, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        $value = $this->get(self::name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPhone($value)
    {
        return $this->set(self::phone, $value);
    }

    /**
     * Returns value of 'phone' property
     *
     * @return string
     */
    public function getPhone()
    {
        $value = $this->get(self::phone);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'commission' property
     *
     * @param double $value Property value
     *
     * @return null
     */
    public function setCommission($value)
    {
        return $this->set(self::commission, $value);
    }

    /**
     * Returns value of 'commission' property
     *
     * @return double
     */
    public function getCommission()
    {
        $value = $this->get(self::commission);
        return $value === null ? (double)$value : $value;
    }

    /**
     * Sets value of 'store_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStoreName($value)
    {
        return $this->set(self::store_name, $value);
    }

    /**
     * Returns value of 'store_name' property
     *
     * @return string
     */
    public function getStoreName()
    {
        $value = $this->get(self::store_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'product_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProductName($value)
    {
        return $this->set(self::product_name, $value);
    }

    /**
     * Returns value of 'product_name' property
     *
     * @return string
     */
    public function getProductName()
    {
        $value = $this->get(self::product_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'product_number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProductNumber($value)
    {
        return $this->set(self::product_number, $value);
    }

    /**
     * Returns value of 'product_number' property
     *
     * @return integer
     */
    public function getProductNumber()
    {
        $value = $this->get(self::product_number);
        return $value === null ? (integer)$value : $value;
    }
}