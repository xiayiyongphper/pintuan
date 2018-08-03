<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderUpdateRequest message
 */
class OrderUpdateRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_number = 1;
    const prepay_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_number => array(
            'name' => 'order_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::prepay_id => array(
            'name' => 'prepay_id',
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
        $this->values[self::order_number] = null;
        $this->values[self::prepay_id] = null;
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
     * Sets value of 'prepay_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPrepayId($value)
    {
        return $this->set(self::prepay_id, $value);
    }

    /**
     * Returns value of 'prepay_id' property
     *
     * @return string
     */
    public function getPrepayId()
    {
        $value = $this->get(self::prepay_id);
        return $value === null ? (string)$value : $value;
    }
}