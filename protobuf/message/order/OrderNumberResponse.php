<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderNumberResponse message
 */
class OrderNumberResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pending_pay = 1;
    const pending_shipped = 2;
    const pending_received = 3;
    const user_received = 4;
    const to_share = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pending_pay => array(
            'name' => 'pending_pay',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pending_shipped => array(
            'name' => 'pending_shipped',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pending_received => array(
            'name' => 'pending_received',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_received => array(
            'name' => 'user_received',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::to_share => array(
            'name' => 'to_share',
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
        $this->values[self::pending_pay] = null;
        $this->values[self::pending_shipped] = null;
        $this->values[self::pending_received] = null;
        $this->values[self::user_received] = null;
        $this->values[self::to_share] = null;
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
     * Sets value of 'pending_pay' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPendingPay($value)
    {
        return $this->set(self::pending_pay, $value);
    }

    /**
     * Returns value of 'pending_pay' property
     *
     * @return integer
     */
    public function getPendingPay()
    {
        $value = $this->get(self::pending_pay);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pending_shipped' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPendingShipped($value)
    {
        return $this->set(self::pending_shipped, $value);
    }

    /**
     * Returns value of 'pending_shipped' property
     *
     * @return integer
     */
    public function getPendingShipped()
    {
        $value = $this->get(self::pending_shipped);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pending_received' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPendingReceived($value)
    {
        return $this->set(self::pending_received, $value);
    }

    /**
     * Returns value of 'pending_received' property
     *
     * @return integer
     */
    public function getPendingReceived()
    {
        $value = $this->get(self::pending_received);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'user_received' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserReceived($value)
    {
        return $this->set(self::user_received, $value);
    }

    /**
     * Returns value of 'user_received' property
     *
     * @return integer
     */
    public function getUserReceived()
    {
        $value = $this->get(self::user_received);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'to_share' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setToShare($value)
    {
        return $this->set(self::to_share, $value);
    }

    /**
     * Returns value of 'to_share' property
     *
     * @return integer
     */
    public function getToShare()
    {
        $value = $this->get(self::to_share);
        return $value === null ? (integer)$value : $value;
    }
}