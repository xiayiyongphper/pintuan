<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * commissionRecord message
 */
class commissionRecord extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const order_id = 2;
    const store_id = 3;
    const type = 4;
    const commission_detail = 5;
    const amount = 6;
    const status = 7;
    const create_at = 8;
    const effect_at = 9;
    const transfer_at = 10;
    const del = 11;
    const product_info = 12;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::order_id => array(
            'name' => 'order_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::commission_detail => array(
            'name' => 'commission_detail',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::amount => array(
            'name' => 'amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::effect_at => array(
            'name' => 'effect_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::transfer_at => array(
            'name' => 'transfer_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::del => array(
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_info => array(
            'name' => 'product_info',
            'repeated' => true,
            'type' => '\message\order\orderProduct'
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
        $this->values[self::order_id] = null;
        $this->values[self::store_id] = null;
        $this->values[self::type] = null;
        $this->values[self::commission_detail] = null;
        $this->values[self::amount] = null;
        $this->values[self::status] = null;
        $this->values[self::create_at] = null;
        $this->values[self::effect_at] = null;
        $this->values[self::transfer_at] = null;
        $this->values[self::del] = null;
        $this->values[self::product_info] = array();
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
     * Sets value of 'order_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderId($value)
    {
        return $this->set(self::order_id, $value);
    }

    /**
     * Returns value of 'order_id' property
     *
     * @return integer
     */
    public function getOrderId()
    {
        $value = $this->get(self::order_id);
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
     * Sets value of 'commission_detail' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCommissionDetail($value)
    {
        return $this->set(self::commission_detail, $value);
    }

    /**
     * Returns value of 'commission_detail' property
     *
     * @return string
     */
    public function getCommissionDetail()
    {
        $value = $this->get(self::commission_detail);
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
     * Sets value of 'effect_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEffectAt($value)
    {
        return $this->set(self::effect_at, $value);
    }

    /**
     * Returns value of 'effect_at' property
     *
     * @return string
     */
    public function getEffectAt()
    {
        $value = $this->get(self::effect_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'transfer_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTransferAt($value)
    {
        return $this->set(self::transfer_at, $value);
    }

    /**
     * Returns value of 'transfer_at' property
     *
     * @return string
     */
    public function getTransferAt()
    {
        $value = $this->get(self::transfer_at);
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
     * Appends value to 'product_info' list
     *
     * @param \message\order\orderProduct $value Value to append
     *
     * @return null
     */
    public function appendProductInfo(\message\order\orderProduct $value)
    {
        return $this->append(self::product_info, $value);
    }

    /**
     * Clears 'product_info' list
     *
     * @return null
     */
    public function clearProductInfo()
    {
        return $this->clear(self::product_info);
    }

    /**
     * Returns 'product_info' list
     *
     * @return \message\order\orderProduct[]
     */
    public function getProductInfo()
    {
        return $this->get(self::product_info);
    }

    /**
     * Returns 'product_info' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductInfoIterator()
    {
        return new \ArrayIterator($this->get(self::product_info));
    }

    /**
     * Returns element from 'product_info' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\orderProduct
     */
    public function getProductInfoAt($offset)
    {
        return $this->get(self::product_info, $offset);
    }

    /**
     * Returns count of 'product_info' list
     *
     * @return int
     */
    public function getProductInfoCount()
    {
        return $this->count(self::product_info);
    }
}