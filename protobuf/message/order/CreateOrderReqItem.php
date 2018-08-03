<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * CreateOrderReqItem message
 */
class CreateOrderReqItem extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_id = 1;
    const specification_id = 2;
    const pintuan_id = 3;
    const product_num = 4;
    const name = 5;
    const wholesaler_id = 6;
    const images = 7;
    const description = 8;
    const third_category_id = 9;
    const item_detail = 10;
    const purchase_price = 11;
    const pick_commission = 12;
    const promote_commission = 13;
    const deal_price = 14;
    const pintuan_price = 15;
    const pintuan_activity_id = 16;
    const price = 17;
    const new_user_price = 18;
    const buy_chains_id = 19;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::specification_id => array(
            'name' => 'specification_id',
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
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::images => array(
            'name' => 'images',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::description => array(
            'name' => 'description',
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
        self::deal_price => array(
            'name' => 'deal_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_price => array(
            'name' => 'pintuan_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::price => array(
            'name' => 'price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::new_user_price => array(
            'name' => 'new_user_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
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
        $this->values[self::specification_id] = null;
        $this->values[self::pintuan_id] = null;
        $this->values[self::product_num] = null;
        $this->values[self::name] = null;
        $this->values[self::wholesaler_id] = null;
        $this->values[self::images] = null;
        $this->values[self::description] = null;
        $this->values[self::third_category_id] = null;
        $this->values[self::item_detail] = null;
        $this->values[self::purchase_price] = null;
        $this->values[self::pick_commission] = null;
        $this->values[self::promote_commission] = null;
        $this->values[self::deal_price] = null;
        $this->values[self::pintuan_price] = null;
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::price] = null;
        $this->values[self::new_user_price] = null;
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
     * Sets value of 'images' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImages($value)
    {
        return $this->set(self::images, $value);
    }

    /**
     * Returns value of 'images' property
     *
     * @return string
     */
    public function getImages()
    {
        $value = $this->get(self::images);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'deal_price' property
     *
     * @param integer $value Property value
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
     * @return integer
     */
    public function getDealPrice()
    {
        $value = $this->get(self::deal_price);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pintuan_price' property
     *
     * @param integer $value Property value
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
     * @return integer
     */
    public function getPintuanPrice()
    {
        $value = $this->get(self::pintuan_price);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'price' property
     *
     * @param integer $value Property value
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
     * @return integer
     */
    public function getPrice()
    {
        $value = $this->get(self::price);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'new_user_price' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setNewUserPrice($value)
    {
        return $this->set(self::new_user_price, $value);
    }

    /**
     * Returns value of 'new_user_price' property
     *
     * @return integer
     */
    public function getNewUserPrice()
    {
        $value = $this->get(self::new_user_price);
        return $value === null ? (integer)$value : $value;
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