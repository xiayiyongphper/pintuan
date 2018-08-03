<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * ThirdCategoryReq message
 */
class ThirdCategoryReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wholesaler_ids = 1;
    const second_category_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wholesaler_ids => array(
            'name' => 'wholesaler_ids',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::second_category_id => array(
            'name' => 'second_category_id',
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
        $this->values[self::wholesaler_ids] = array();
        $this->values[self::second_category_id] = null;
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
     * Sets value of 'second_category_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSecondCategoryId($value)
    {
        return $this->set(self::second_category_id, $value);
    }

    /**
     * Returns value of 'second_category_id' property
     *
     * @return integer
     */
    public function getSecondCategoryId()
    {
        $value = $this->get(self::second_category_id);
        return $value === null ? (integer)$value : $value;
    }
}