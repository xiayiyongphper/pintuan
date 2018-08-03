<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WholesalerRequest message
 */
class WholesalerRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wholesaler_id = 1;
    const store_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
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
        $this->values[self::wholesaler_id] = array();
        $this->values[self::store_id] = array();
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

    /**
     * Appends value to 'store_id' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendStoreId($value)
    {
        return $this->append(self::store_id, $value);
    }

    /**
     * Clears 'store_id' list
     *
     * @return null
     */
    public function clearStoreId()
    {
        return $this->clear(self::store_id);
    }

    /**
     * Returns 'store_id' list
     *
     * @return integer[]
     */
    public function getStoreId()
    {
        return $this->get(self::store_id);
    }

    /**
     * Returns 'store_id' iterator
     *
     * @return \ArrayIterator
     */
    public function getStoreIdIterator()
    {
        return new \ArrayIterator($this->get(self::store_id));
    }

    /**
     * Returns element from 'store_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getStoreIdAt($offset)
    {
        return $this->get(self::store_id, $offset);
    }

    /**
     * Returns count of 'store_id' list
     *
     * @return int
     */
    public function getStoreIdCount()
    {
        return $this->count(self::store_id);
    }
}