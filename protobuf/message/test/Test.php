<?php
/**
 *
 * message.test package
 */

namespace message\test;
/**
 * Test message
 */
class Test extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const test = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::test => array(
            'name' => 'test',
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
        $this->values[self::test] = null;
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
     * Sets value of 'test' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTest($value)
    {
        return $this->set(self::test, $value);
    }

    /**
     * Returns value of 'test' property
     *
     * @return string
     */
    public function getTest()
    {
        $value = $this->get(self::test);
        return $value === null ? (string)$value : $value;
    }
}