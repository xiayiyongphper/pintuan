<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * GuideJoinGroupRes message
 */
class GuideJoinGroupRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const title = 1;
    const nick_name = 2;
    const qrcode = 3;
    const message = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::title => array(
            'name' => 'title',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::qrcode => array(
            'name' => 'qrcode',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::message => array(
            'name' => 'message',
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
        $this->values[self::title] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::qrcode] = null;
        $this->values[self::message] = null;
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
     * Sets value of 'title' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTitle($value)
    {
        return $this->set(self::title, $value);
    }

    /**
     * Returns value of 'title' property
     *
     * @return string
     */
    public function getTitle()
    {
        $value = $this->get(self::title);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'qrcode' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setQrcode($value)
    {
        return $this->set(self::qrcode, $value);
    }

    /**
     * Returns value of 'qrcode' property
     *
     * @return string
     */
    public function getQrcode()
    {
        $value = $this->get(self::qrcode);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'message' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMessage($value)
    {
        return $this->set(self::message, $value);
    }

    /**
     * Returns value of 'message' property
     *
     * @return string
     */
    public function getMessage()
    {
        $value = $this->get(self::message);
        return $value === null ? (string)$value : $value;
    }
}