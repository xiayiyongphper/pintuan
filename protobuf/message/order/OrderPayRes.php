<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderPayRes message
 */
class OrderPayRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_number = 1;
    const pintuan_id = 2;
    const order_type = 3;
    const user_id = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_number => array(
            'name' => 'order_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_type => array(
            'name' => 'order_type',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
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
        $this->values[self::order_number] = null;
        $this->values[self::pintuan_id] = null;
        $this->values[self::order_type] = null;
        $this->values[self::user_id] = null;
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
     * Sets value of 'pintuan_id' property
     *
     * @param string $value Property value
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
     * @return string
     */
    public function getPintuanId()
    {
        $value = $this->get(self::pintuan_id);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'order_type' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOrderType($value)
    {
        return $this->set(self::order_type, $value);
    }

    /**
     * Returns value of 'order_type' property
     *
     * @return string
     */
    public function getOrderType()
    {
        $value = $this->get(self::order_type);
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
}