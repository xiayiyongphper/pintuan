<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * getShareCouponResponse message
 */
class getShareCouponResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const count = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::count => array(
            'name' => 'count',
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
        $this->values[self::count] = null;
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
     * Sets value of 'count' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCount($value)
    {
        return $this->set(self::count, $value);
    }

    /**
     * Returns value of 'count' property
     *
     * @return string
     */
    public function getCount()
    {
        $value = $this->get(self::count);
        return $value === null ? (string)$value : $value;
    }
}