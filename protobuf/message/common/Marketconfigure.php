<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Marketconfigure message
 */
class Marketconfigure extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const custom_nickname = 2;
    const custom_qrcode = 3;
    const solitaire_success_msg = 4;
    const invite_btn_msg = 5;
    const invite_colonel_banner = 6;
    const colonel_describe_img = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::custom_nickname => array(
            'name' => 'custom_nickname',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::custom_qrcode => array(
            'name' => 'custom_qrcode',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::solitaire_success_msg => array(
            'name' => 'solitaire_success_msg',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::invite_btn_msg => array(
            'name' => 'invite_btn_msg',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::invite_colonel_banner => array(
            'name' => 'invite_colonel_banner',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::colonel_describe_img => array(
            'name' => 'colonel_describe_img',
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
        $this->values[self::custom_nickname] = null;
        $this->values[self::custom_qrcode] = null;
        $this->values[self::solitaire_success_msg] = null;
        $this->values[self::invite_btn_msg] = null;
        $this->values[self::invite_colonel_banner] = null;
        $this->values[self::colonel_describe_img] = null;
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
     * Sets value of 'custom_nickname' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCustomNickname($value)
    {
        return $this->set(self::custom_nickname, $value);
    }

    /**
     * Returns value of 'custom_nickname' property
     *
     * @return string
     */
    public function getCustomNickname()
    {
        $value = $this->get(self::custom_nickname);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'custom_qrcode' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCustomQrcode($value)
    {
        return $this->set(self::custom_qrcode, $value);
    }

    /**
     * Returns value of 'custom_qrcode' property
     *
     * @return string
     */
    public function getCustomQrcode()
    {
        $value = $this->get(self::custom_qrcode);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'solitaire_success_msg' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSolitaireSuccessMsg($value)
    {
        return $this->set(self::solitaire_success_msg, $value);
    }

    /**
     * Returns value of 'solitaire_success_msg' property
     *
     * @return string
     */
    public function getSolitaireSuccessMsg()
    {
        $value = $this->get(self::solitaire_success_msg);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'invite_btn_msg' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setInviteBtnMsg($value)
    {
        return $this->set(self::invite_btn_msg, $value);
    }

    /**
     * Returns value of 'invite_btn_msg' property
     *
     * @return string
     */
    public function getInviteBtnMsg()
    {
        $value = $this->get(self::invite_btn_msg);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'invite_colonel_banner' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setInviteColonelBanner($value)
    {
        return $this->set(self::invite_colonel_banner, $value);
    }

    /**
     * Returns value of 'invite_colonel_banner' property
     *
     * @return string
     */
    public function getInviteColonelBanner()
    {
        $value = $this->get(self::invite_colonel_banner);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'colonel_describe_img' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setColonelDescribeImg($value)
    {
        return $this->set(self::colonel_describe_img, $value);
    }

    /**
     * Returns value of 'colonel_describe_img' property
     *
     * @return string
     */
    public function getColonelDescribeImg()
    {
        $value = $this->get(self::colonel_describe_img);
        return $value === null ? (string)$value : $value;
    }
}