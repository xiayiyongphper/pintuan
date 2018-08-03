<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Header message
 */
class Header extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const route = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::route => array(
            'name' => 'route',
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
        $this->values[self::route] = null;
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
     * Sets value of 'route' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRoute($value)
    {
        return $this->set(self::route, $value);
    }

    /**
     * Returns value of 'route' property
     *
     * @return string
     */
    public function getRoute()
    {
        $value = $this->get(self::route);
        return $value === null ? (string)$value : $value;
    }
}