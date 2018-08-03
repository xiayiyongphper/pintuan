<?php
/**
 *
 * message.pay package
 */

namespace message\pay;
/**
 * WxNotifyOrderRequest message
 */
class WxNotifyOrderRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const data = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::data => array(
            'name' => 'data',
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
        $this->values[self::data] = null;
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
     * Sets value of 'data' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setData($value)
    {
        return $this->set(self::data, $value);
    }

    /**
     * Returns value of 'data' property
     *
     * @return string
     */
    public function getData()
    {
        $value = $this->get(self::data);
        return $value === null ? (string)$value : $value;
    }
}