<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * createArrivalBillSaveRes message
 */
class createArrivalBillSaveRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const arrival_code = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_code => array(
            'name' => 'arrival_code',
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
        $this->values[self::id] = null;
        $this->values[self::arrival_code] = null;
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
     * Sets value of 'id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::id, $value);
    }

    /**
     * Returns value of 'id' property
     *
     * @return integer
     */
    public function getId()
    {
        $value = $this->get(self::id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'arrival_code' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setArrivalCode($value)
    {
        return $this->set(self::arrival_code, $value);
    }

    /**
     * Returns value of 'arrival_code' property
     *
     * @return string
     */
    public function getArrivalCode()
    {
        $value = $this->get(self::arrival_code);
        return $value === null ? (string)$value : $value;
    }
}