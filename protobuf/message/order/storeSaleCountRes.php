<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * storeSaleCountRes message
 */
class storeSaleCountRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_count = 1;
    const product_count = 2;
    const amount_count = 3;
    const commission_count = 4;
    const start_date = 5;
    const end_date = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_count => array(
            'name' => 'order_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_count => array(
            'name' => 'product_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::amount_count => array(
            'name' => 'amount_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::commission_count => array(
            'name' => 'commission_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::start_date => array(
            'name' => 'start_date',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::end_date => array(
            'name' => 'end_date',
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
        $this->values[self::order_count] = null;
        $this->values[self::product_count] = null;
        $this->values[self::amount_count] = null;
        $this->values[self::commission_count] = null;
        $this->values[self::start_date] = null;
        $this->values[self::end_date] = null;
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
     * Sets value of 'order_count' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderCount($value)
    {
        return $this->set(self::order_count, $value);
    }

    /**
     * Returns value of 'order_count' property
     *
     * @return integer
     */
    public function getOrderCount()
    {
        $value = $this->get(self::order_count);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'product_count' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProductCount($value)
    {
        return $this->set(self::product_count, $value);
    }

    /**
     * Returns value of 'product_count' property
     *
     * @return integer
     */
    public function getProductCount()
    {
        $value = $this->get(self::product_count);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'amount_count' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAmountCount($value)
    {
        return $this->set(self::amount_count, $value);
    }

    /**
     * Returns value of 'amount_count' property
     *
     * @return string
     */
    public function getAmountCount()
    {
        $value = $this->get(self::amount_count);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'commission_count' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCommissionCount($value)
    {
        return $this->set(self::commission_count, $value);
    }

    /**
     * Returns value of 'commission_count' property
     *
     * @return string
     */
    public function getCommissionCount()
    {
        $value = $this->get(self::commission_count);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'start_date' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStartDate($value)
    {
        return $this->set(self::start_date, $value);
    }

    /**
     * Returns value of 'start_date' property
     *
     * @return string
     */
    public function getStartDate()
    {
        $value = $this->get(self::start_date);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'end_date' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEndDate($value)
    {
        return $this->set(self::end_date, $value);
    }

    /**
     * Returns value of 'end_date' property
     *
     * @return string
     */
    public function getEndDate()
    {
        $value = $this->get(self::end_date);
        return $value === null ? (string)$value : $value;
    }
}