<?php
/**
 *
 * message.pay package
 */

namespace message\pay;
/**
 * WxUnifiedOrderRequest message
 */
class WxUnifiedOrderRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const body = 1;
    const attach = 2;
    const out_trade_no = 3;
    const total_fee = 4;
    const openid = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::body => array(
            'name' => 'body',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::attach => array(
            'name' => 'attach',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::out_trade_no => array(
            'name' => 'out_trade_no',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::total_fee => array(
            'name' => 'total_fee',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::openid => array(
            'name' => 'openid',
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
        $this->values[self::body] = null;
        $this->values[self::attach] = null;
        $this->values[self::out_trade_no] = null;
        $this->values[self::total_fee] = null;
        $this->values[self::openid] = null;
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
     * Sets value of 'body' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBody($value)
    {
        return $this->set(self::body, $value);
    }

    /**
     * Returns value of 'body' property
     *
     * @return string
     */
    public function getBody()
    {
        $value = $this->get(self::body);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'attach' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAttach($value)
    {
        return $this->set(self::attach, $value);
    }

    /**
     * Returns value of 'attach' property
     *
     * @return string
     */
    public function getAttach()
    {
        $value = $this->get(self::attach);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'out_trade_no' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOutTradeNo($value)
    {
        return $this->set(self::out_trade_no, $value);
    }

    /**
     * Returns value of 'out_trade_no' property
     *
     * @return string
     */
    public function getOutTradeNo()
    {
        $value = $this->get(self::out_trade_no);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'total_fee' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setTotalFee($value)
    {
        return $this->set(self::total_fee, $value);
    }

    /**
     * Returns value of 'total_fee' property
     *
     * @return integer
     */
    public function getTotalFee()
    {
        $value = $this->get(self::total_fee);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'openid' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOpenid($value)
    {
        return $this->set(self::openid, $value);
    }

    /**
     * Returns value of 'openid' property
     *
     * @return string
     */
    public function getOpenid()
    {
        $value = $this->get(self::openid);
        return $value === null ? (string)$value : $value;
    }
}