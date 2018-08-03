<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * Specification message
 */
class Specification extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const specification_id = 1;
    const price = 2;
    const pintuan_activity_id = 3;
    const pintuan_price = 4;
    const attribute = 5;
    const new_price = 6;
    const image = 7;

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
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_price => array(
            'name' => 'pintuan_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::attribute => array(
            'name' => 'attribute',
            'repeated' => true,
            'type' => '\message\common\KeyValueItem'
        ),
        self::new_price => array(
            'name' => 'new_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::image => array(
            'name' => 'image',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::pintuan_price] = null;
        $this->values[self::attribute] = array();
        $this->values[self::new_price] = null;
        $this->values[self::image] = null;
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
     * Sets value of 'new_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNewPrice($value)
    {
        return $this->set(self::new_price, $value);
    }

    /**
     * Returns value of 'new_price' property
     *
     * @return string
     */
    public function getNewPrice()
    {
        $value = $this->get(self::new_price);
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
}