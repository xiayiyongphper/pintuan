<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderDetailOriginal message
 */
class orderDetailOriginal extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const status = 2;
    const real_amount = 3;
    const order_number = 4;
    const create_at = 5;
    const enable_deliver_time = 6;
    const pick_code = 7;
    const store_name = 8;
    const type = 9;
    const pintuan_activity_id = 10;
    const address_nick_name = 11;
    const address_phone = 12;
    const address = 13;
    const coupon_id = 14;
    const discount_amount = 15;
    const order_product = 16;
    const pintuan_id = 17;
    const payable_amount = 18;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::real_amount => array(
            'name' => 'real_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_number => array(
            'name' => 'order_number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::enable_deliver_time => array(
            'name' => 'enable_deliver_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pick_code => array(
            'name' => 'pick_code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_name => array(
            'name' => 'store_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        self::address_nick_name => array(
            'name' => 'address_nick_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::address_phone => array(
            'name' => 'address_phone',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::address => array(
            'name' => 'address',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::coupon_id => array(
            'name' => 'coupon_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::discount_amount => array(
            'name' => 'discount_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_product => array(
            'name' => 'order_product',
            'repeated' => true,
            'type' => '\message\order\orderProduct'
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::payable_amount => array(
            'name' => 'payable_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::status] = null;
        $this->values[self::real_amount] = null;
        $this->values[self::order_number] = null;
        $this->values[self::create_at] = null;
        $this->values[self::enable_deliver_time] = null;
        $this->values[self::pick_code] = null;
        $this->values[self::store_name] = null;
        $this->values[self::type] = null;
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::address_nick_name] = null;
        $this->values[self::address_phone] = null;
        $this->values[self::address] = null;
        $this->values[self::coupon_id] = null;
        $this->values[self::discount_amount] = null;
        $this->values[self::order_product] = array();
        $this->values[self::pintuan_id] = null;
        $this->values[self::payable_amount] = null;
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
     * Sets value of 'enable_deliver_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEnableDeliverTime($value)
    {
        return $this->set(self::enable_deliver_time, $value);
    }

    /**
     * Returns value of 'enable_deliver_time' property
     *
     * @return string
     */
    public function getEnableDeliverTime()
    {
        $value = $this->get(self::enable_deliver_time);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'address_nick_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddressNickName($value)
    {
        return $this->set(self::address_nick_name, $value);
    }

    /**
     * Returns value of 'address_nick_name' property
     *
     * @return string
     */
    public function getAddressNickName()
    {
        $value = $this->get(self::address_nick_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'address_phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddressPhone($value)
    {
        return $this->set(self::address_phone, $value);
    }

    /**
     * Returns value of 'address_phone' property
     *
     * @return string
     */
    public function getAddressPhone()
    {
        $value = $this->get(self::address_phone);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'address' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddress($value)
    {
        return $this->set(self::address, $value);
    }

    /**
     * Returns value of 'address' property
     *
     * @return string
     */
    public function getAddress()
    {
        $value = $this->get(self::address);
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
     * Sets value of 'discount_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDiscountAmount($value)
    {
        return $this->set(self::discount_amount, $value);
    }

    /**
     * Returns value of 'discount_amount' property
     *
     * @return string
     */
    public function getDiscountAmount()
    {
        $value = $this->get(self::discount_amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'order_product' list
     *
     * @param \message\order\orderProduct $value Value to append
     *
     * @return null
     */
    public function appendOrderProduct(\message\order\orderProduct $value)
    {
        return $this->append(self::order_product, $value);
    }

    /**
     * Clears 'order_product' list
     *
     * @return null
     */
    public function clearOrderProduct()
    {
        return $this->clear(self::order_product);
    }

    /**
     * Returns 'order_product' list
     *
     * @return \message\order\orderProduct[]
     */
    public function getOrderProduct()
    {
        return $this->get(self::order_product);
    }

    /**
     * Returns 'order_product' iterator
     *
     * @return \ArrayIterator
     */
    public function getOrderProductIterator()
    {
        return new \ArrayIterator($this->get(self::order_product));
    }

    /**
     * Returns element from 'order_product' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\orderProduct
     */
    public function getOrderProductAt($offset)
    {
        return $this->get(self::order_product, $offset);
    }

    /**
     * Returns count of 'order_product' list
     *
     * @return int
     */
    public function getOrderProductCount()
    {
        return $this->count(self::order_product);
    }

    /**
     * Sets value of 'pintuan_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanId($value)
    {
        return $this->set(self::pintuan_id, $value);
    }

    /**
     * Returns value of 'pintuan_id' property
     *
     * @return integer
     */
    public function getPintuanId()
    {
        $value = $this->get(self::pintuan_id);
        return $value === null ? (integer)$value : $value;
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
}