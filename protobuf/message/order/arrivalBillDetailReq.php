<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBillDetailReq message
 */
class arrivalBillDetailReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const arrival_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_id => array(
            'name' => 'arrival_id',
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
        $this->values[self::store_id] = null;
        $this->values[self::arrival_id] = null;
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
     * Sets value of 'store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreId($value)
    {
        return $this->set(self::store_id, $value);
    }

    /**
     * Returns value of 'store_id' property
     *
     * @return integer
     */
    public function getStoreId()
    {
        $value = $this->get(self::store_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'arrival_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setArrivalId($value)
    {
        return $this->set(self::arrival_id, $value);
    }

    /**
     * Returns value of 'arrival_id' property
     *
     * @return string
     */
    public function getArrivalId()
    {
        $value = $this->get(self::arrival_id);
        return $value === null ? (string)$value : $value;
    }
}