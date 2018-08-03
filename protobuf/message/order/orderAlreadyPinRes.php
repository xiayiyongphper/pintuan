<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderAlreadyPinRes message
 */
class orderAlreadyPinRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const number = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::number => array(
            'name' => 'number',
            'required' => true,
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
        $this->values[self::number] = null;
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
     * Sets value of 'number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setNumber($value)
    {
        return $this->set(self::number, $value);
    }

    /**
     * Returns value of 'number' property
     *
     * @return integer
     */
    public function getNumber()
    {
        $value = $this->get(self::number);
        return $value === null ? (integer)$value : $value;
    }
}