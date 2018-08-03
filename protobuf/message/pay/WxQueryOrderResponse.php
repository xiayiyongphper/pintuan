<?php
/**
 *
 * message.pay package
 */

namespace message\pay;
/**
 * WxQueryOrderResponse message
 */
class WxQueryOrderResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const status = 1;
    const order_number = 2;
    const msg = 3;
    const total_fee = 4;
    const bank_type = 5;
    const settlement_total_fee = 6;
    const transaction_id = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::status => array(
            'name' => 'status',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_number => array(
            'name' => 'order_number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::msg => array(
            'name' => 'msg',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::total_fee => array(
            'name' => 'total_fee',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::bank_type => array(
            'name' => 'bank_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::settlement_total_fee => array(
            'name' => 'settlement_total_fee',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::transaction_id => array(
            'name' => 'transaction_id',
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
        $this->values[self::status] = null;
        $this->values[self::order_number] = null;
        $this->values[self::msg] = null;
        $this->values[self::total_fee] = null;
        $this->values[self::bank_type] = null;
        $this->values[self::settlement_total_fee] = null;
        $this->values[self::transaction_id] = null;
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
     * Sets value of 'status' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStatus($value)
    {
        return $this->set(self::status, $value);
    }

    /**
     * Returns value of 'status' property
     *
     * @return string
     */
    public function getStatus()
    {
        $value = $this->get(self::status);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'order_number' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOrderNumber($value)
    {
        return $this->set(self::order_number, $value);
    }

    /**
     * Returns value of 'order_number' property
     *
     * @return string
     */
    public function getOrderNumber()
    {
        $value = $this->get(self::order_number);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'msg' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMsg($value)
    {
        return $this->set(self::msg, $value);
    }

    /**
     * Returns value of 'msg' property
     *
     * @return string
     */
    public function getMsg()
    {
        $value = $this->get(self::msg);
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
     * Sets value of 'bank_type' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBankType($value)
    {
        return $this->set(self::bank_type, $value);
    }

    /**
     * Returns value of 'bank_type' property
     *
     * @return string
     */
    public function getBankType()
    {
        $value = $this->get(self::bank_type);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'settlement_total_fee' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSettlementTotalFee($value)
    {
        return $this->set(self::settlement_total_fee, $value);
    }

    /**
     * Returns value of 'settlement_total_fee' property
     *
     * @return integer
     */
    public function getSettlementTotalFee()
    {
        $value = $this->get(self::settlement_total_fee);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'transaction_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTransactionId($value)
    {
        return $this->set(self::transaction_id, $value);
    }

    /**
     * Returns value of 'transaction_id' property
     *
     * @return string
     */
    public function getTransactionId()
    {
        $value = $this->get(self::transaction_id);
        return $value === null ? (string)$value : $value;
    }
}