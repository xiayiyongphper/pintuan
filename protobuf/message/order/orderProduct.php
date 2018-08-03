<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderProduct message
 */
class orderProduct extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const order_id = 2;
    const product_id = 3;
    const pintuan_id = 4;
    const specification_id = 5;
    const number = 6;
    const deal_price = 7;
    const name = 8;
    const wholesaler_id = 9;
    const images = 10;
    const description = 11;
    const unit = 12;
    const third_category_id = 13;
    const item_detail = 14;
    const purchase_price = 15;
    const pick_commission = 16;
    const promote_commission = 17;
    const price = 18;
    const pintuan_price = 19;
    const create_at = 20;
    const update_at = 21;
    const del = 22;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::order_id => array(
            'name' => 'order_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_id => array(
            'name' => 'product_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_id => array(
            'name' => 'pintuan_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::specification_id => array(
            'name' => 'specification_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::number => array(
            'name' => 'number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::deal_price => array(
            'name' => 'deal_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::name => array(
            'name' => 'name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::images => array(
            'name' => 'images',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::description => array(
            'name' => 'description',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::unit => array(
            'name' => 'unit',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::third_category_id => array(
            'name' => 'third_category_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::item_detail => array(
            'name' => 'item_detail',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::purchase_price => array(
            'name' => 'purchase_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pick_commission => array(
            'name' => 'pick_commission',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::promote_commission => array(
            'name' => 'promote_commission',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::price => array(
            'name' => 'price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan_price => array(
            'name' => 'pintuan_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::update_at => array(
            'name' => 'update_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::del => array(
            'name' => 'del',
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
        $this->values[self::id] = null;
        $this->values[self::order_id] = null;
        $this->values[self::product_id] = null;
        $this->values[self::pintuan_id] = null;
        $this->values[self::specification_id] = null;
        $this->values[self::number] = null;
        $this->values[self::deal_price] = null;
        $this->values[self::name] = null;
        $this->values[self::wholesaler_id] = null;
        $this->values[self::images] = array();
        $this->values[self::description] = null;
        $this->values[self::unit] = null;
        $this->values[self::third_category_id] = null;
        $this->values[self::item_detail] = null;
        $this->values[self::purchase_price] = null;
        $this->values[self::pick_commission] = null;
        $this->values[self::promote_commission] = null;
        $this->values[self::price] = null;
        $this->values[self::pintuan_price] = null;
        $this->values[self::create_at] = null;
        $this->values[self::update_at] = null;
        $this->values[self::del] = null;
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
     * Sets value of 'id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::id, $value);
    }

    /**
     * Returns value of 'id' property
     *
     * @return integer
     */
    public function getId()
    {
        $value = $this->get(self::id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'order_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderId($value)
    {
        return $this->set(self::order_id, $value);
    }

    /**
     * Returns value of 'order_id' property
     *
     * @return integer
     */
    public function getOrderId()
    {
        $value = $this->get(self::order_id);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setNumber($value)
    {
        return $this->set(self::number, $value);
    }

    /**
     * Returns value of 'number' property
     *
     * @return integer
     */
    public function getNumber()
    {
        $value = $this->get(self::number);
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
     * Appends value to 'images' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendImages($value)
    {
        return $this->append(self::images, $value);
    }

    /**
     * Clears 'images' list
     *
     * @return null
     */
    public function clearImages()
    {
        return $this->clear(self::images);
    }

    /**
     * Returns 'images' list
     *
     * @return string[]
     */
    public function getImages()
    {
        return $this->get(self::images);
    }

    /**
     * Returns 'images' iterator
     *
     * @return \ArrayIterator
     */
    public function getImagesIterator()
    {
        return new \ArrayIterator($this->get(self::images));
    }

    /**
     * Returns element from 'images' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getImagesAt($offset)
    {
        return $this->get(self::images, $offset);
    }

    /**
     * Returns count of 'images' list
     *
     * @return int
     */
    public function getImagesCount()
    {
        return $this->count(self::images);
    }

    /**
     * Sets value of 'description' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDescription($value)
    {
        return $this->set(self::description, $value);
    }

    /**
     * Returns value of 'description' property
     *
     * @return string
     */
    public function getDescription()
    {
        $value = $this->get(self::description);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'unit' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUnit($value)
    {
        return $this->set(self::unit, $value);
    }

    /**
     * Returns value of 'unit' property
     *
     * @return string
     */
    public function getUnit()
    {
        $value = $this->get(self::unit);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'purchase_price' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPurchasePrice($value)
    {
        return $this->set(self::purchase_price, $value);
    }

    /**
     * Returns value of 'purchase_price' property
     *
     * @return integer
     */
    public function getPurchasePrice()
    {
        $value = $this->get(self::purchase_price);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pick_commission' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPickCommission($value)
    {
        return $this->set(self::pick_commission, $value);
    }

    /**
     * Returns value of 'pick_commission' property
     *
     * @return integer
     */
    public function getPickCommission()
    {
        $value = $this->get(self::pick_commission);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'promote_commission' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPromoteCommission($value)
    {
        return $this->set(self::promote_commission, $value);
    }

    /**
     * Returns value of 'promote_commission' property
     *
     * @return integer
     */
    public function getPromoteCommission()
    {
        $value = $this->get(self::promote_commission);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'pintuan_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPintuanPrice($value)
    {
        return $this->set(self::pintuan_price, $value);
    }

    /**
     * Returns value of 'pintuan_price' property
     *
     * @return string
     */
    public function getPintuanPrice()
    {
        $value = $this->get(self::pintuan_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'create_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCreateAt($value)
    {
        return $this->set(self::create_at, $value);
    }

    /**
     * Returns value of 'create_at' property
     *
     * @return string
     */
    public function getCreateAt()
    {
        $value = $this->get(self::create_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'update_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUpdateAt($value)
    {
        return $this->set(self::update_at, $value);
    }

    /**
     * Returns value of 'update_at' property
     *
     * @return string
     */
    public function getUpdateAt()
    {
        $value = $this->get(self::update_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'del' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDel($value)
    {
        return $this->set(self::del, $value);
    }

    /**
     * Returns value of 'del' property
     *
     * @return integer
     */
    public function getDel()
    {
        $value = $this->get(self::del);
        return $value === null ? (integer)$value : $value;
    }
}