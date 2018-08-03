<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * BuyChainsUser message
 */
class BuyChainsUser extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const serial_number = 1;
    const user_id = 2;
    const buy_number = 3;
    const buy_time = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::serial_number => array(
            'name' => 'serial_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::buy_number => array(
            'name' => 'buy_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::buy_time => array(
            'name' => 'buy_time',
            'required' => true,
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
        $this->values[self::serial_number] = null;
        $this->values[self::user_id] = null;
        $this->values[self::buy_number] = null;
        $this->values[self::buy_time] = null;
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
     * Sets value of 'serial_number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSerialNumber($value)
    {
        return $this->set(self::serial_number, $value);
    }

    /**
     * Returns value of 'serial_number' property
     *
     * @return integer
     */
    public function getSerialNumber()
    {
        $value = $this->get(self::serial_number);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'buy_number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setBuyNumber($value)
    {
        return $this->set(self::buy_number, $value);
    }

    /**
     * Returns value of 'buy_number' property
     *
     * @return integer
     */
    public function getBuyNumber()
    {
        $value = $this->get(self::buy_number);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'buy_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBuyTime($value)
    {
        return $this->set(self::buy_time, $value);
    }

    /**
     * Returns value of 'buy_time' property
     *
     * @return string
     */
    public function getBuyTime()
    {
        $value = $this->get(self::buy_time);
        return $value === null ? (string)$value : $value;
    }
}