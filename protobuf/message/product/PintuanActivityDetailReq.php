<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanActivityDetailReq message
 */
class PintuanActivityDetailReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const activity_id = 1;
    const store_id = 2;
    const wholesaler_id = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::activity_id => array(
            'name' => 'activity_id',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'repeated' => true,
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
        $this->values[self::activity_id] = array();
        $this->values[self::store_id] = null;
        $this->values[self::wholesaler_id] = array();
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
     * Appends value to 'activity_id' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendActivityId($value)
    {
        return $this->append(self::activity_id, $value);
    }

    /**
     * Clears 'activity_id' list
     *
     * @return null
     */
    public function clearActivityId()
    {
        return $this->clear(self::activity_id);
    }

    /**
     * Returns 'activity_id' list
     *
     * @return integer[]
     */
    public function getActivityId()
    {
        return $this->get(self::activity_id);
    }

    /**
     * Returns 'activity_id' iterator
     *
     * @return \ArrayIterator
     */
    public function getActivityIdIterator()
    {
        return new \ArrayIterator($this->get(self::activity_id));
    }

    /**
     * Returns element from 'activity_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getActivityIdAt($offset)
    {
        return $this->get(self::activity_id, $offset);
    }

    /**
     * Returns count of 'activity_id' list
     *
     * @return int
     */
    public function getActivityIdCount()
    {
        return $this->count(self::activity_id);
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
     * Appends value to 'wholesaler_id' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendWholesalerId($value)
    {
        return $this->append(self::wholesaler_id, $value);
    }

    /**
     * Clears 'wholesaler_id' list
     *
     * @return null
     */
    public function clearWholesalerId()
    {
        return $this->clear(self::wholesaler_id);
    }

    /**
     * Returns 'wholesaler_id' list
     *
     * @return integer[]
     */
    public function getWholesalerId()
    {
        return $this->get(self::wholesaler_id);
    }

    /**
     * Returns 'wholesaler_id' iterator
     *
     * @return \ArrayIterator
     */
    public function getWholesalerIdIterator()
    {
        return new \ArrayIterator($this->get(self::wholesaler_id));
    }

    /**
     * Returns element from 'wholesaler_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getWholesalerIdAt($offset)
    {
        return $this->get(self::wholesaler_id, $offset);
    }

    /**
     * Returns count of 'wholesaler_id' list
     *
     * @return int
     */
    public function getWholesalerIdCount()
    {
        return $this->count(self::wholesaler_id);
    }
}