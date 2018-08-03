<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * GetPayOrderCountRes message
 */
class GetPayOrderCountRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_count = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_count => array(
            'name' => 'order_count',
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
        $this->values[self::order_count] = null;
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
     * Sets value of 'order_count' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderCount($value)
    {
        return $this->set(self::order_count, $value);
    }

    /**
     * Returns value of 'order_count' property
     *
     * @return integer
     */
    public function getOrderCount()
    {
        $value = $this->get(self::order_count);
        return $value === null ? (integer)$value : $value;
    }
}