<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * ShareConfig message
 */
class ShareConfig extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const type = 2;
    const position = 3;
    const img_url = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::position => array(
            'name' => 'position',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::img_url => array(
            'name' => 'img_url',
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
        $this->values[self::id] = null;
        $this->values[self::type] = null;
        $this->values[self::position] = null;
        $this->values[self::img_url] = null;
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
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'position' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPosition($value)
    {
        return $this->set(self::position, $value);
    }

    /**
     * Returns value of 'position' property
     *
     * @return integer
     */
    public function getPosition()
    {
        $value = $this->get(self::position);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'img_url' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImgUrl($value)
    {
        return $this->set(self::img_url, $value);
    }

    /**
     * Returns value of 'img_url' property
     *
     * @return string
     */
    public function getImgUrl()
    {
        $value = $this->get(self::img_url);
        return $value === null ? (string)$value : $value;
    }
}