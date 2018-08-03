<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * ProductListReq message
 */
class ProductListReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wholesaler_ids = 1;
    const third_category_id = 2;
    const page = 3;
    const page_size = 4;
    const topic_id = 5;
    const activity_id = 6;
    const rule_id = 7;
    const product_ids = 8;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wholesaler_ids => array(
            'name' => 'wholesaler_ids',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::third_category_id => array(
            'name' => 'third_category_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::page => array(
            'name' => 'page',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::page_size => array(
            'name' => 'page_size',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::topic_id => array(
            'name' => 'topic_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::activity_id => array(
            'name' => 'activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::rule_id => array(
            'name' => 'rule_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_ids => array(
            'name' => 'product_ids',
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
        $this->values[self::wholesaler_ids] = array();
        $this->values[self::third_category_id] = null;
        $this->values[self::page] = null;
        $this->values[self::page_size] = null;
        $this->values[self::topic_id] = null;
        $this->values[self::activity_id] = null;
        $this->values[self::rule_id] = null;
        $this->values[self::product_ids] = array();
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
     * Sets value of 'third_category_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setThirdCategoryId($value)
    {
        return $this->set(self::third_category_id, $value);
    }

    /**
     * Returns value of 'third_category_id' property
     *
     * @return integer
     */
    public function getThirdCategoryId()
    {
        $value = $this->get(self::third_category_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'page' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPage($value)
    {
        return $this->set(self::page, $value);
    }

    /**
     * Returns value of 'page' property
     *
     * @return integer
     */
    public function getPage()
    {
        $value = $this->get(self::page);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'page_size' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPageSize($value)
    {
        return $this->set(self::page_size, $value);
    }

    /**
     * Returns value of 'page_size' property
     *
     * @return integer
     */
    public function getPageSize()
    {
        $value = $this->get(self::page_size);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'topic_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setTopicId($value)
    {
        return $this->set(self::topic_id, $value);
    }

    /**
     * Returns value of 'topic_id' property
     *
     * @return integer
     */
    public function getTopicId()
    {
        $value = $this->get(self::topic_id);
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
     * Sets value of 'rule_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setRuleId($value)
    {
        return $this->set(self::rule_id, $value);
    }

    /**
     * Returns value of 'rule_id' property
     *
     * @return integer
     */
    public function getRuleId()
    {
        $value = $this->get(self::rule_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'product_ids' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendProductIds($value)
    {
        return $this->append(self::product_ids, $value);
    }

    /**
     * Clears 'product_ids' list
     *
     * @return null
     */
    public function clearProductIds()
    {
        return $this->clear(self::product_ids);
    }

    /**
     * Returns 'product_ids' list
     *
     * @return integer[]
     */
    public function getProductIds()
    {
        return $this->get(self::product_ids);
    }

    /**
     * Returns 'product_ids' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductIdsIterator()
    {
        return new \ArrayIterator($this->get(self::product_ids));
    }

    /**
     * Returns element from 'product_ids' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getProductIdsAt($offset)
    {
        return $this->get(self::product_ids, $offset);
    }

    /**
     * Returns count of 'product_ids' list
     *
     * @return int
     */
    public function getProductIdsCount()
    {
        return $this->count(self::product_ids);
    }
}