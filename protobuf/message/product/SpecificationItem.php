<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * SpecificationItem message
 */
class SpecificationItem extends \framework\protocolbuffers\Message
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
            'repeated' => true,
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
        $this->values[self::value] = array();
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
     * Appends value to 'value' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendValue($value)
    {
        return $this->append(self::value, $value);
    }

    /**
     * Clears 'value' list
     *
     * @return null
     */
    public function clearValue()
    {
        return $this->clear(self::value);
    }

    /**
     * Returns 'value' list
     *
     * @return string[]
     */
    public function getValue()
    {
        return $this->get(self::value);
    }

    /**
     * Returns 'value' iterator
     *
     * @return \ArrayIterator
     */
    public function getValueIterator()
    {
        return new \ArrayIterator($this->get(self::value));
    }

    /**
     * Returns element from 'value' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getValueAt($offset)
    {
        return $this->get(self::value, $offset);
    }

    /**
     * Returns count of 'value' list
     *
     * @return int
     */
    public function getValueCount()
    {
        return $this->count(self::value);
    }
}