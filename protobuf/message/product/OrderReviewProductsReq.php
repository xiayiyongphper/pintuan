<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * OrderReviewProductsReq message
 */
class OrderReviewProductsReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const items = 1;
    const wholesaler_ids = 2;
    const store_id = 3;
    const type = 4;
    const activity_id = 5;
    const user_id = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::items => array(
            'name' => 'items',
            'repeated' => true,
            'type' => '\message\common\Item'
        ),
        self::wholesaler_ids => array(
            'name' => 'wholesaler_ids',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::type => array(
            'name' => 'type',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::activity_id => array(
            'name' => 'activity_id',
            'required' => false,
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
        $this->values[self::items] = array();
        $this->values[self::wholesaler_ids] = array();
        $this->values[self::store_id] = null;
        $this->values[self::type] = null;
        $this->values[self::activity_id] = null;
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
     * Appends value to 'items' list
     *
     * @param \message\common\Item $value Value to append
     *
     * @return null
     */
    public function appendItems(\message\common\Item $value)
    {
        return $this->append(self::items, $value);
    }

    /**
     * Clears 'items' list
     *
     * @return null
     */
    public function clearItems()
    {
        return $this->clear(self::items);
    }

    /**
     * Returns 'items' list
     *
     * @return \message\common\Item[]
     */
    public function getItems()
    {
        return $this->get(self::items);
    }

    /**
     * Returns 'items' iterator
     *
     * @return \ArrayIterator
     */
    public function getItemsIterator()
    {
        return new \ArrayIterator($this->get(self::items));
    }

    /**
     * Returns element from 'items' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Item
     */
    public function getItemsAt($offset)
    {
        return $this->get(self::items, $offset);
    }

    /**
     * Returns count of 'items' list
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->count(self::items);
    }

    /**
     * Appends value to 'wholesaler_ids' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendWholesalerIds($value)
    {
        return $this->append(self::wholesaler_ids, $value);
    }

    /**
     * Clears 'wholesaler_ids' list
     *
     * @return null
     */
    public function clearWholesalerIds()
    {
        return $this->clear(self::wholesaler_ids);
    }

    /**
     * Returns 'wholesaler_ids' list
     *
     * @return integer[]
     */
    public function getWholesalerIds()
    {
        return $this->get(self::wholesaler_ids);
    }

    /**
     * Returns 'wholesaler_ids' iterator
     *
     * @return \ArrayIterator
     */
    public function getWholesalerIdsIterator()
    {
        return new \ArrayIterator($this->get(self::wholesaler_ids));
    }

    /**
     * Returns element from 'wholesaler_ids' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getWholesalerIdsAt($offset)
    {
        return $this->get(self::wholesaler_ids, $offset);
    }

    /**
     * Returns count of 'wholesaler_ids' list
     *
     * @return int
     */
    public function getWholesalerIdsCount()
    {
        return $this->count(self::wholesaler_ids);
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
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setActivityId($value)
    {
        return $this->set(self::activity_id, $value);
    }

    /**
     * Returns value of 'activity_id' property
     *
     * @return integer
     */
    public function getActivityId()
    {
        $value = $this->get(self::activity_id);
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