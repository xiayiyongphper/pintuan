<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * OrderProduct message
 */
class OrderProduct extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_id = 1;
    const pintuan_id = 2;
    const product_num = 3;
    const name = 4;
    const image = 5;
    const price = 6;
    const item_detail = 7;
    const deal_price = 8;
    const specification_id = 9;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_num => array(
            'name' => 'product_num',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::name => array(
            'name' => 'name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::image => array(
            'name' => 'image',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::price => array(
            'name' => 'price',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::item_detail => array(
            'name' => 'item_detail',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::deal_price => array(
            'name' => 'deal_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::specification_id => array(
            'name' => 'specification_id',
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
        $this->values[self::pintuan_id] = null;
        $this->values[self::product_num] = null;
        $this->values[self::name] = null;
        $this->values[self::image] = null;
        $this->values[self::price] = null;
        $this->values[self::item_detail] = null;
        $this->values[self::deal_price] = null;
        $this->values[self::specification_id] = null;
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
     * Sets value of 'item_detail' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setItemDetail($value)
    {
        return $this->set(self::item_detail, $value);
    }

    /**
     * Returns value of 'item_detail' property
     *
     * @return string
     */
    public function getItemDetail()
    {
        $value = $this->get(self::item_detail);
        return $value === null ? (string)$value : $value;
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
}