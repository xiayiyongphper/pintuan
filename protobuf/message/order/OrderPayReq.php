<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderPayReq message
 */
class OrderPayReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_number = 1;
    const pay_amount = 2;
    const bank_type = 3;
    const settlement_total_fee = 4;
    const transaction_id = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_number => array(
            'name' => 'order_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pay_amount => array(
            'name' => 'pay_amount',
            'required' => true,
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
        $this->values[self::order_number] = null;
        $this->values[self::pay_amount] = null;
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
     * Sets value of 'pay_amount' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPayAmount($value)
    {
        return $this->set(self::pay_amount, $value);
    }

    /**
     * Returns value of 'pay_amount' property
     *
     * @return integer
     */
    public function getPayAmount()
    {
        $value = $this->get(self::pay_amount);
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