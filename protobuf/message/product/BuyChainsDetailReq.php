<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * BuyChainsDetailReq message
 */
class BuyChainsDetailReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const buy_chains_id = 1;
    const user_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::buy_chains_id => array(
            'name' => 'buy_chains_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
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
        $this->values[self::buy_chains_id] = null;
        $this->values[self::user_id] = null;
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
     * Sets value of 'buy_chains_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setBuyChainsId($value)
    {
        return $this->set(self::buy_chains_id, $value);
    }

    /**
     * Returns value of 'buy_chains_id' property
     *
     * @return integer
     */
    public function getBuyChainsId()
    {
        $value = $this->get(self::buy_chains_id);
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
}