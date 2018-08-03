<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanStartReq message
 */
class PintuanStartReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pintuan_activity_id = 1;
    const user_id = 2;
    const nick_name = 3;
    const avatar_url = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::avatar_url => array(
            'name' => 'avatar_url',
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
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::user_id] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::avatar_url] = null;
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
     * Sets value of 'pintuan_activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanActivityId($value)
    {
        return $this->set(self::pintuan_activity_id, $value);
    }

    /**
     * Returns value of 'pintuan_activity_id' property
     *
     * @return integer
     */
    public function getPintuanActivityId()
    {
        $value = $this->get(self::pintuan_activity_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::user_id, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return integer
     */
    public function getUserId()
    {
        $value = $this->get(self::user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'nick_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNickName($value)
    {
        return $this->set(self::nick_name, $value);
    }

    /**
     * Returns value of 'nick_name' property
     *
     * @return string
     */
    public function getNickName()
    {
        $value = $this->get(self::nick_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'avatar_url' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAvatarUrl($value)
    {
        return $this->set(self::avatar_url, $value);
    }

    /**
     * Returns value of 'avatar_url' property
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        $value = $this->get(self::avatar_url);
        return $value === null ? (string)$value : $value;
    }
}