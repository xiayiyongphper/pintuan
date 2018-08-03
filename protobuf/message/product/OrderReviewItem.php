<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * OrderReviewItem message
 */
class OrderReviewItem extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_id = 1;
    const name = 2;
    const price = 3;
    const image = 4;
    const specification_id = 5;
    const specification_desc = 6;
    const product_num = 7;
    const pintuan_id = 8;
    const wholesaler_id = 9;
    const pintuan_store_ids = 10;
    const pintuan_activity_id = 11;
    const deal_price = 12;
    const buy_chains_id = 13;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::name => array(
            'name' => 'name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::price => array(
            'name' => 'price',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::image => array(
            'name' => 'image',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::specification_id => array(
            'name' => 'specification_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::specification_desc => array(
            'name' => 'specification_desc',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_num => array(
            'name' => 'product_num',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_store_ids => array(
            'name' => 'pintuan_store_ids',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::deal_price => array(
            'name' => 'deal_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::buy_chains_id => array(
            'name' => 'buy_chains_id',
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
        $this->values[self::product_id] = null;
        $this->values[self::name] = null;
        $this->values[self::price] = null;
        $this->values[self::image] = null;
        $this->values[self::specification_id] = null;
        $this->values[self::specification_desc] = null;
        $this->values[self::product_num] = null;
        $this->values[self::pintuan_id] = null;
        $this->values[self::wholesaler_id] = null;
        $this->values[self::pintuan_store_ids] = array();
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::deal_price] = null;
        $this->values[self::buy_chains_id] = null;
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
     * Sets value of 'product_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProductId($value)
    {
        return $this->set(self::product_id, $value);
    }

    /**
     * Returns value of 'product_id' property
     *
     * @return integer
     */
    public function getProductId()
    {
        $value = $this->get(self::product_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::name, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        $value = $this->get(self::name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPrice($value)
    {
        return $this->set(self::price, $value);
    }

    /**
     * Returns value of 'price' property
     *
     * @return string
     */
    public function getPrice()
    {
        $value = $this->get(self::price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'image' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImage($value)
    {
        return $this->set(self::image, $value);
    }

    /**
     * Returns value of 'image' property
     *
     * @return string
     */
    public function getImage()
    {
        $value = $this->get(self::image);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'specification_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSpecificationId($value)
    {
        return $this->set(self::specification_id, $value);
    }

    /**
     * Returns value of 'specification_id' property
     *
     * @return integer
     */
    public function getSpecificationId()
    {
        $value = $this->get(self::specification_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'specification_desc' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSpecificationDesc($value)
    {
        return $this->set(self::specification_desc, $value);
    }

    /**
     * Returns value of 'specification_desc' property
     *
     * @return string
     */
    public function getSpecificationDesc()
    {
        $value = $this->get(self::specification_desc);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'product_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProductNum($value)
    {
        return $this->set(self::product_num, $value);
    }

    /**
     * Returns value of 'product_num' property
     *
     * @return integer
     */
    public function getProductNum()
    {
        $value = $this->get(self::product_num);
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

    /**
     * Sets value of 'wholesaler_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setWholesalerId($value)
    {
        return $this->set(self::wholesaler_id, $value);
    }

    /**
     * Returns value of 'wholesaler_id' property
     *
     * @return integer
     */
    public function getWholesalerId()
    {
        $value = $this->get(self::wholesaler_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'pintuan_store_ids' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendPintuanStoreIds($value)
    {
        return $this->append(self::pintuan_store_ids, $value);
    }

    /**
     * Clears 'pintuan_store_ids' list
     *
     * @return null
     */
    public function clearPintuanStoreIds()
    {
        return $this->clear(self::pintuan_store_ids);
    }

    /**
     * Returns 'pintuan_store_ids' list
     *
     * @return integer[]
     */
    public function getPintuanStoreIds()
    {
        return $this->get(self::pintuan_store_ids);
    }

    /**
     * Returns 'pintuan_store_ids' iterator
     *
     * @return \ArrayIterator
     */
    public function getPintuanStoreIdsIterator()
    {
        return new \ArrayIterator($this->get(self::pintuan_store_ids));
    }

    /**
     * Returns element from 'pintuan_store_ids' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getPintuanStoreIdsAt($offset)
    {
        return $this->get(self::pintuan_store_ids, $offset);
    }

    /**
     * Returns count of 'pintuan_store_ids' list
     *
     * @return int
     */
    public function getPintuanStoreIdsCount()
    {
        return $this->count(self::pintuan_store_ids);
    }

    /**
     * Sets value of 'pintuan_activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanActivityId($value)
    {
        return $this->set(self::pintuan_activity_id, $value);
    }

    /**
     * Returns value of 'pintuan_activity_id' property
     *
     * @return integer
     */
    public function getPintuanActivityId()
    {
        $value = $this->get(self::pintuan_activity_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'deal_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDealPrice($value)
    {
        return $this->set(self::deal_price, $value);
    }

    /**
     * Returns value of 'deal_price' property
     *
     * @return string
     */
    public function getDealPrice()
    {
        $value = $this->get(self::deal_price);
        return $value === null ? (string)$value : $value;
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
}