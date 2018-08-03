<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * KeyValueItem message
 */
class KeyValueItem extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const key = 1;
    const value = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::key => array(
            'name' => 'key',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::value => array(
            'name' => 'value',
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
        $this->values[self::key] = null;
        $this->values[self::value] = null;
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
     * Sets value of 'key' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setKey($value)
    {
        return $this->set(self::key, $value);
    }

    /**
     * Returns value of 'key' property
     *
     * @return string
     */
    public function getKey()
    {
        $value = $this->get(self::key);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'value' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setValue($value)
    {
        return $this->set(self::value, $value);
    }

    /**
     * Returns value of 'value' property
     *
     * @return string
     */
    public function getValue()
    {
        $value = $this->get(self::value);
        return $value === null ? (string)$value : $value;
    }
}