<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * getShareCouponRequest message
 */
class getShareCouponRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_id = 1;
    const pintuan_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
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
        $this->values[self::user_id] = null;
        $this->values[self::pintuan_id] = null;
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
     * Sets value of 'pintuan_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanId($value)
    {
        return $this->set(self::pintuan_id, $value);
    }

    /**
     * Returns value of 'pintuan_id' property
     *
     * @return integer
     */
    public function getPintuanId()
    {
        $value = $this->get(self::pintuan_id);
        return $value === null ? (integer)$value : $value;
    }
}