<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderReviewReqItem message
 */
class OrderReviewReqItem extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_id = 1;
    const product_num = 2;
    const deal_price = 3;
    const new_user_price = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::product_num => array(
            'name' => 'product_num',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::deal_price => array(
            'name' => 'deal_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::new_user_price => array(
            'name' => 'new_user_price',
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
        $this->values[self::product_num] = null;
        $this->values[self::deal_price] = null;
        $this->values[self::new_user_price] = null;
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
}