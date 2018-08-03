<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WalletInfoRes message
 */
class WalletInfoRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wallet = 1;
    const in_cash = 2;
    const total_cash = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wallet => array(
            'name' => 'wallet',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::in_cash => array(
            'name' => 'in_cash',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::total_cash => array(
            'name' => 'total_cash',
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
        $this->values[self::wallet] = null;
        $this->values[self::in_cash] = null;
        $this->values[self::total_cash] = null;
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
     * Sets value of 'wallet' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setWallet($value)
    {
        return $this->set(self::wallet, $value);
    }

    /**
     * Returns value of 'wallet' property
     *
     * @return string
     */
    public function getWallet()
    {
        $value = $this->get(self::wallet);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'in_cash' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setInCash($value)
    {
        return $this->set(self::in_cash, $value);
    }

    /**
     * Returns value of 'in_cash' property
     *
     * @return string
     */
    public function getInCash()
    {
        $value = $this->get(self::in_cash);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'total_cash' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTotalCash($value)
    {
        return $this->set(self::total_cash, $value);
    }

    /**
     * Returns value of 'total_cash' property
     *
     * @return string
     */
    public function getTotalCash()
    {
        $value = $this->get(self::total_cash);
        return $value === null ? (string)$value : $value;
    }
}