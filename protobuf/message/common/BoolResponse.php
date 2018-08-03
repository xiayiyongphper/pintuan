<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * BoolResponse message
 */
class BoolResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const result = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::result => array(
            'name' => 'result',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_BOOL,
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
        $this->values[self::result] = null;
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
     * Sets value of 'result' property
     *
     * @param boolean $value Property value
     *
     * @return null
     */
    public function setResult($value)
    {
        return $this->set(self::result, $value);
    }

    /**
     * Returns value of 'result' property
     *
     * @return boolean
     */
    public function getResult()
    {
        $value = $this->get(self::result);
        return $value === null ? (boolean)$value : $value;
    }
}