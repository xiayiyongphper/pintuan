<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * CreateOrderReq message
 */
class CreateOrderReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_id = 1;
    const store_id = 2;
    const items = 3;
    const receiver_name = 4;
    const receiver_phone = 5;
    const address = 6;
    const type = 7;
    const store_name = 8;
    const coupon_id = 9;
    const include_new_user_product = 10;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::items => array(
            'name' => 'items',
            'repeated' => true,
            'type' => '\message\order\CreateOrderReqItem'
        ),
        self::receiver_name => array(
            'name' => 'receiver_name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::receiver_phone => array(
            'name' => 'receiver_phone',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::address => array(
            'name' => 'address',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::type => array(
            'name' => 'type',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_name => array(
            'name' => 'store_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::coupon_id => array(
            'name' => 'coupon_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::include_new_user_product => array(
            'name' => 'include_new_user_product',
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
        $this->values[self::user_id] = null;
        $this->values[self::store_id] = null;
        $this->values[self::items] = array();
        $this->values[self::receiver_name] = null;
        $this->values[self::receiver_phone] = null;
        $this->values[self::address] = null;
        $this->values[self::type] = null;
        $this->values[self::store_name] = null;
        $this->values[self::coupon_id] = null;
        $this->values[self::include_new_user_product] = null;
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
     * Appends value to 'items' list
     *
     * @param \message\order\CreateOrderReqItem $value Value to append
     *
     * @return null
     */
    public function appendItems(\message\order\CreateOrderReqItem $value)
    {
        return $this->append(self::items, $value);
    }

    /**
     * Clears 'items' list
     *
     * @return null
     */
    public function clearItems()
    {
        return $this->clear(self::items);
    }

    /**
     * Returns 'items' list
     *
     * @return \message\order\CreateOrderReqItem[]
     */
    public function getItems()
    {
        return $this->get(self::items);
    }

    /**
     * Returns 'items' iterator
     *
     * @return \ArrayIterator
     */
    public function getItemsIterator()
    {
        return new \ArrayIterator($this->get(self::items));
    }

    /**
     * Returns element from 'items' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\CreateOrderReqItem
     */
    public function getItemsAt($offset)
    {
        return $this->get(self::items, $offset);
    }

    /**
     * Returns count of 'items' list
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->count(self::items);
    }

    /**
     * Sets value of 'receiver_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setReceiverName($value)
    {
        return $this->set(self::receiver_name, $value);
    }

    /**
     * Returns value of 'receiver_name' property
     *
     * @return string
     */
    public function getReceiverName()
    {
        $value = $this->get(self::receiver_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'receiver_phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setReceiverPhone($value)
    {
        return $this->set(self::receiver_phone, $value);
    }

    /**
     * Returns value of 'receiver_phone' property
     *
     * @return string
     */
    public function getReceiverPhone()
    {
        $value = $this->get(self::receiver_phone);
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
     * Sets value of 'include_new_user_product' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIncludeNewUserProduct($value)
    {
        return $this->set(self::include_new_user_product, $value);
    }

    /**
     * Returns value of 'include_new_user_product' property
     *
     * @return integer
     */
    public function getIncludeNewUserProduct()
    {
        $value = $this->get(self::include_new_user_product);
        return $value === null ? (integer)$value : $value;
    }
}