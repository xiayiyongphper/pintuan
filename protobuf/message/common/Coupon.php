<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Coupon message
 */
class Coupon extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const user_id = 2;
    const state = 3;
    const rule_id = 4;
    const expiration_date = 5;
    const source = 6;
    const created_at = 7;
    const title = 8;
    const discount_amount = 9;
    const condition = 10;
    const unavailable_reason = 11;
    const coupon_tag = 12;
    const validity_time = 13;
    const sales_rule_scope = 14;
    const receive_out = 15;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::state => array(
            'name' => 'state',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::rule_id => array(
            'name' => 'rule_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::expiration_date => array(
            'name' => 'expiration_date',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::source => array(
            'name' => 'source',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::created_at => array(
            'name' => 'created_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::title => array(
            'name' => 'title',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::discount_amount => array(
            'name' => 'discount_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::condition => array(
            'name' => 'condition',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::unavailable_reason => array(
            'name' => 'unavailable_reason',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::coupon_tag => array(
            'name' => 'coupon_tag',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::validity_time => array(
            'name' => 'validity_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::sales_rule_scope => array(
            'name' => 'sales_rule_scope',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::receive_out => array(
            'name' => 'receive_out',
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
        $this->values[self::user_id] = null;
        $this->values[self::state] = null;
        $this->values[self::rule_id] = null;
        $this->values[self::expiration_date] = null;
        $this->values[self::source] = null;
        $this->values[self::created_at] = null;
        $this->values[self::title] = null;
        $this->values[self::discount_amount] = null;
        $this->values[self::condition] = null;
        $this->values[self::unavailable_reason] = null;
        $this->values[self::coupon_tag] = null;
        $this->values[self::validity_time] = null;
        $this->values[self::sales_rule_scope] = null;
        $this->values[self::receive_out] = null;
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
     * Sets value of 'state' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setState($value)
    {
        return $this->set(self::state, $value);
    }

    /**
     * Returns value of 'state' property
     *
     * @return integer
     */
    public function getState()
    {
        $value = $this->get(self::state);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'rule_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setRuleId($value)
    {
        return $this->set(self::rule_id, $value);
    }

    /**
     * Returns value of 'rule_id' property
     *
     * @return integer
     */
    public function getRuleId()
    {
        $value = $this->get(self::rule_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'expiration_date' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setExpirationDate($value)
    {
        return $this->set(self::expiration_date, $value);
    }

    /**
     * Returns value of 'expiration_date' property
     *
     * @return string
     */
    public function getExpirationDate()
    {
        $value = $this->get(self::expiration_date);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'source' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSource($value)
    {
        return $this->set(self::source, $value);
    }

    /**
     * Returns value of 'source' property
     *
     * @return integer
     */
    public function getSource()
    {
        $value = $this->get(self::source);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'created_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCreatedAt($value)
    {
        return $this->set(self::created_at, $value);
    }

    /**
     * Returns value of 'created_at' property
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $value = $this->get(self::created_at);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'discount_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDiscountAmount($value)
    {
        return $this->set(self::discount_amount, $value);
    }

    /**
     * Returns value of 'discount_amount' property
     *
     * @return string
     */
    public function getDiscountAmount()
    {
        $value = $this->get(self::discount_amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'condition' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCondition($value)
    {
        return $this->set(self::condition, $value);
    }

    /**
     * Returns value of 'condition' property
     *
     * @return string
     */
    public function getCondition()
    {
        $value = $this->get(self::condition);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'unavailable_reason' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUnavailableReason($value)
    {
        return $this->set(self::unavailable_reason, $value);
    }

    /**
     * Returns value of 'unavailable_reason' property
     *
     * @return string
     */
    public function getUnavailableReason()
    {
        $value = $this->get(self::unavailable_reason);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'coupon_tag' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCouponTag($value)
    {
        return $this->set(self::coupon_tag, $value);
    }

    /**
     * Returns value of 'coupon_tag' property
     *
     * @return string
     */
    public function getCouponTag()
    {
        $value = $this->get(self::coupon_tag);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'validity_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setValidityTime($value)
    {
        return $this->set(self::validity_time, $value);
    }

    /**
     * Returns value of 'validity_time' property
     *
     * @return string
     */
    public function getValidityTime()
    {
        $value = $this->get(self::validity_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'sales_rule_scope' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSalesRuleScope($value)
    {
        return $this->set(self::sales_rule_scope, $value);
    }

    /**
     * Returns value of 'sales_rule_scope' property
     *
     * @return integer
     */
    public function getSalesRuleScope()
    {
        $value = $this->get(self::sales_rule_scope);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'receive_out' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setReceiveOut($value)
    {
        return $this->set(self::receive_out, $value);
    }

    /**
     * Returns value of 'receive_out' property
     *
     * @return integer
     */
    public function getReceiveOut()
    {
        $value = $this->get(self::receive_out);
        return $value === null ? (integer)$value : $value;
    }
}