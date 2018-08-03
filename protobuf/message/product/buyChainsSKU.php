<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * buyChainsSKU message
 */
class buyChainsSKU extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const specification_id = 1;
    const price = 2;
    const activity_price = 3;
    const attribute = 4;
    const sold_number = 5;
    const qty = 6;
    const limit_buy_num = 7;
    const already_buy_num = 8;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::specification_id => array(
            'name' => 'specification_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::price => array(
            'name' => 'price',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::activity_price => array(
            'name' => 'activity_price',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::attribute => array(
            'name' => 'attribute',
            'repeated' => true,
            'type' => '\message\common\KeyValueItem'
        ),
        self::sold_number => array(
            'name' => 'sold_number',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::qty => array(
            'name' => 'qty',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::limit_buy_num => array(
            'name' => 'limit_buy_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::already_buy_num => array(
            'name' => 'already_buy_num',
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
        $this->values[self::specification_id] = null;
        $this->values[self::price] = null;
        $this->values[self::activity_price] = null;
        $this->values[self::attribute] = array();
        $this->values[self::sold_number] = null;
        $this->values[self::qty] = null;
        $this->values[self::limit_buy_num] = null;
        $this->values[self::already_buy_num] = null;
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
     * Sets value of 'activity_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setActivityPrice($value)
    {
        return $this->set(self::activity_price, $value);
    }

    /**
     * Returns value of 'activity_price' property
     *
     * @return string
     */
    public function getActivityPrice()
    {
        $value = $this->get(self::activity_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'attribute' list
     *
     * @param \message\common\KeyValueItem $value Value to append
     *
     * @return null
     */
    public function appendAttribute(\message\common\KeyValueItem $value)
    {
        return $this->append(self::attribute, $value);
    }

    /**
     * Clears 'attribute' list
     *
     * @return null
     */
    public function clearAttribute()
    {
        return $this->clear(self::attribute);
    }

    /**
     * Returns 'attribute' list
     *
     * @return \message\common\KeyValueItem[]
     */
    public function getAttribute()
    {
        return $this->get(self::attribute);
    }

    /**
     * Returns 'attribute' iterator
     *
     * @return \ArrayIterator
     */
    public function getAttributeIterator()
    {
        return new \ArrayIterator($this->get(self::attribute));
    }

    /**
     * Returns element from 'attribute' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\KeyValueItem
     */
    public function getAttributeAt($offset)
    {
        return $this->get(self::attribute, $offset);
    }

    /**
     * Returns count of 'attribute' list
     *
     * @return int
     */
    public function getAttributeCount()
    {
        return $this->count(self::attribute);
    }

    /**
     * Sets value of 'sold_number' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSoldNumber($value)
    {
        return $this->set(self::sold_number, $value);
    }

    /**
     * Returns value of 'sold_number' property
     *
     * @return integer
     */
    public function getSoldNumber()
    {
        $value = $this->get(self::sold_number);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'qty' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setQty($value)
    {
        return $this->set(self::qty, $value);
    }

    /**
     * Returns value of 'qty' property
     *
     * @return integer
     */
    public function getQty()
    {
        $value = $this->get(self::qty);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'limit_buy_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setLimitBuyNum($value)
    {
        return $this->set(self::limit_buy_num, $value);
    }

    /**
     * Returns value of 'limit_buy_num' property
     *
     * @return integer
     */
    public function getLimitBuyNum()
    {
        $value = $this->get(self::limit_buy_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'already_buy_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setAlreadyBuyNum($value)
    {
        return $this->set(self::already_buy_num, $value);
    }

    /**
     * Returns value of 'already_buy_num' property
     *
     * @return integer
     */
    public function getAlreadyBuyNum()
    {
        $value = $this->get(self::already_buy_num);
        return $value === null ? (integer)$value : $value;
    }
}