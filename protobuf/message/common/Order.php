<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Order message
 */
class Order extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const status = 2;
    const status_label = 3;
    const amount = 4;
    const order_number = 5;
    const create_at = 6;
    const order_product = 7;
    const order_product_num = 8;
    const pintuan_activity_id = 9;
    const type = 10;
    const pick_code = 11;
    const store_name = 12;
    const payable_amount = 13;
    const pintuan_full_time = 14;
    const user_id = 15;
    const pintuan_id = 16;

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
        self::status_label => array(
            'name' => 'status_label',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::amount => array(
            'name' => 'amount',
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
        self::order_product => array(
            'name' => 'order_product',
            'repeated' => true,
            'type' => '\message\common\OrderProduct'
        ),
        self::order_product_num => array(
            'name' => 'order_product_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
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
        self::payable_amount => array(
            'name' => 'payable_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan_full_time => array(
            'name' => 'pintuan_full_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
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
        $this->values[self::status] = null;
        $this->values[self::status_label] = null;
        $this->values[self::amount] = null;
        $this->values[self::order_number] = null;
        $this->values[self::create_at] = null;
        $this->values[self::order_product] = array();
        $this->values[self::order_product_num] = null;
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::type] = null;
        $this->values[self::pick_code] = null;
        $this->values[self::store_name] = null;
        $this->values[self::payable_amount] = null;
        $this->values[self::pintuan_full_time] = null;
        $this->values[self::user_id] = null;
        $this->values[self::pintuan_id] = null;
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
     * Sets value of 'status_label' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStatusLabel($value)
    {
        return $this->set(self::status_label, $value);
    }

    /**
     * Returns value of 'status_label' property
     *
     * @return string
     */
    public function getStatusLabel()
    {
        $value = $this->get(self::status_label);
        return $value === null ? (string)$value : $value;
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
     * Appends value to 'order_product' list
     *
     * @param \message\common\OrderProduct $value Value to append
     *
     * @return null
     */
    public function appendOrderProduct(\message\common\OrderProduct $value)
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
     * @return \message\common\OrderProduct[]
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
     * @return \message\common\OrderProduct
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
     * Sets value of 'order_product_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderProductNum($value)
    {
        return $this->set(self::order_product_num, $value);
    }

    /**
     * Returns value of 'order_product_num' property
     *
     * @return integer
     */
    public function getOrderProductNum()
    {
        $value = $this->get(self::order_product_num);
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
     * Sets value of 'pintuan_full_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPintuanFullTime($value)
    {
        return $this->set(self::pintuan_full_time, $value);
    }

    /**
     * Returns value of 'pintuan_full_time' property
     *
     * @return string
     */
    public function getPintuanFullTime()
    {
        $value = $this->get(self::pintuan_full_time);
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
}