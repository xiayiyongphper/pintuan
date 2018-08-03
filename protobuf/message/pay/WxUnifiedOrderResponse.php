<?php
/**
 *
 * message.pay package
 */

namespace message\pay;
/**
 * WxUnifiedOrderResponse message
 */
class WxUnifiedOrderResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const appid = 1;
    const timestamp = 2;
    const noncestr = 3;
    const package = 4;
    const signtype = 5;
    const paysign = 6;
    const prepay_id = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::appid => array(
            'name' => 'appId',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::timestamp => array(
            'name' => 'timeStamp',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::noncestr => array(
            'name' => 'nonceStr',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::package => array(
            'name' => 'package',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::signtype => array(
            'name' => 'signType',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::paysign => array(
            'name' => 'paySign',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::prepay_id => array(
            'name' => 'prepay_id',
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
        $this->values[self::appid] = null;
        $this->values[self::timestamp] = null;
        $this->values[self::noncestr] = null;
        $this->values[self::package] = null;
        $this->values[self::signtype] = null;
        $this->values[self::paysign] = null;
        $this->values[self::prepay_id] = null;
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
     * Sets value of 'appId' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAppId($value)
    {
        return $this->set(self::appid, $value);
    }

    /**
     * Returns value of 'appId' property
     *
     * @return string
     */
    public function getAppId()
    {
        $value = $this->get(self::appid);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'timeStamp' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTimeStamp($value)
    {
        return $this->set(self::timestamp, $value);
    }

    /**
     * Returns value of 'timeStamp' property
     *
     * @return string
     */
    public function getTimeStamp()
    {
        $value = $this->get(self::timestamp);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'nonceStr' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNonceStr($value)
    {
        return $this->set(self::noncestr, $value);
    }

    /**
     * Returns value of 'nonceStr' property
     *
     * @return string
     */
    public function getNonceStr()
    {
        $value = $this->get(self::noncestr);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'package' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPackage($value)
    {
        return $this->set(self::package, $value);
    }

    /**
     * Returns value of 'package' property
     *
     * @return string
     */
    public function getPackage()
    {
        $value = $this->get(self::package);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'signType' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSignType($value)
    {
        return $this->set(self::signtype, $value);
    }

    /**
     * Returns value of 'signType' property
     *
     * @return string
     */
    public function getSignType()
    {
        $value = $this->get(self::signtype);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'paySign' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPaySign($value)
    {
        return $this->set(self::paysign, $value);
    }

    /**
     * Returns value of 'paySign' property
     *
     * @return string
     */
    public function getPaySign()
    {
        $value = $this->get(self::paysign);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'prepay_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPrepayId($value)
    {
        return $this->set(self::prepay_id, $value);
    }

    /**
     * Returns value of 'prepay_id' property
     *
     * @return string
     */
    public function getPrepayId()
    {
        $value = $this->get(self::prepay_id);
        return $value === null ? (string)$value : $value;
    }
}