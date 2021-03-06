<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderVerificationSaveReq message
 */
class orderVerificationSaveReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const pick_code = 2;
    const order_id = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pick_code => array(
            'name' => 'pick_code',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_id => array(
            'name' => 'order_id',
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
        $this->values[self::store_id] = null;
        $this->values[self::pick_code] = null;
        $this->values[self::order_id] = null;
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
     * Sets value of 'order_id' property
     *
     * @param string $value Property value
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
     * @return string
     */
    public function getOrderId()
    {
        $value = $this->get(self::order_id);
        return $value === null ? (string)$value : $value;
    }
}