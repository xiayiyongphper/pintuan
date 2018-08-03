<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBill message
 */
class arrivalBill extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const arrival_code = 2;
    const sku_num = 3;
    const arrival_total = 4;
    const order_num = 5;
    const create_at = 6;
    const del = 7;
    const remark = 8;
    const should_arrival_total = 9;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_code => array(
            'name' => 'arrival_code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::sku_num => array(
            'name' => 'sku_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_total => array(
            'name' => 'arrival_total',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::order_num => array(
            'name' => 'order_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::del => array(
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::remark => array(
            'name' => 'remark',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::should_arrival_total => array(
            'name' => 'should_arrival_total',
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
        $this->values[self::arrival_code] = null;
        $this->values[self::sku_num] = null;
        $this->values[self::arrival_total] = null;
        $this->values[self::order_num] = null;
        $this->values[self::create_at] = null;
        $this->values[self::del] = null;
        $this->values[self::remark] = null;
        $this->values[self::should_arrival_total] = null;
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
     * Sets value of 'arrival_code' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setArrivalCode($value)
    {
        return $this->set(self::arrival_code, $value);
    }

    /**
     * Returns value of 'arrival_code' property
     *
     * @return string
     */
    public function getArrivalCode()
    {
        $value = $this->get(self::arrival_code);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'sku_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSkuNum($value)
    {
        return $this->set(self::sku_num, $value);
    }

    /**
     * Returns value of 'sku_num' property
     *
     * @return integer
     */
    public function getSkuNum()
    {
        $value = $this->get(self::sku_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'arrival_total' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setArrivalTotal($value)
    {
        return $this->set(self::arrival_total, $value);
    }

    /**
     * Returns value of 'arrival_total' property
     *
     * @return integer
     */
    public function getArrivalTotal()
    {
        $value = $this->get(self::arrival_total);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'order_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderNum($value)
    {
        return $this->set(self::order_num, $value);
    }

    /**
     * Returns value of 'order_num' property
     *
     * @return integer
     */
    public function getOrderNum()
    {
        $value = $this->get(self::order_num);
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
     * Sets value of 'remark' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRemark($value)
    {
        return $this->set(self::remark, $value);
    }

    /**
     * Returns value of 'remark' property
     *
     * @return string
     */
    public function getRemark()
    {
        $value = $this->get(self::remark);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'should_arrival_total' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setShouldArrivalTotal($value)
    {
        return $this->set(self::should_arrival_total, $value);
    }

    /**
     * Returns value of 'should_arrival_total' property
     *
     * @return integer
     */
    public function getShouldArrivalTotal()
    {
        $value = $this->get(self::should_arrival_total);
        return $value === null ? (integer)$value : $value;
    }
}