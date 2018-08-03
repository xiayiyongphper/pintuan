<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanIdDetailRes message
 */
class PintuanIdDetailRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const end_time = 2;
    const pintuan_need_num = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::end_time => array(
            'name' => 'end_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan_need_num => array(
            'name' => 'pintuan_need_num',
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
        $this->values[self::id] = null;
        $this->values[self::end_time] = null;
        $this->values[self::pintuan_need_num] = null;
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
     * Sets value of 'end_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEndTime($value)
    {
        return $this->set(self::end_time, $value);
    }

    /**
     * Returns value of 'end_time' property
     *
     * @return string
     */
    public function getEndTime()
    {
        $value = $this->get(self::end_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'pintuan_need_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanNeedNum($value)
    {
        return $this->set(self::pintuan_need_num, $value);
    }

    /**
     * Returns value of 'pintuan_need_num' property
     *
     * @return integer
     */
    public function getPintuanNeedNum()
    {
        $value = $this->get(self::pintuan_need_num);
        return $value === null ? (integer)$value : $value;
    }
}