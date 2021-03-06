<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * BuyChainsProductDetailRes message
 */
class BuyChainsProductDetailRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const buy_chains_id = 1;
    const images = 2;
    const name = 3;
    const description = 4;
    const end_time = 5;
    const specification = 6;
    const specification_item = 7;
    const product_id = 8;
    const sub_name = 9;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::buy_chains_id => array(
            'name' => 'buy_chains_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::images => array(
            'name' => 'images',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::name => array(
            'name' => 'name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::description => array(
            'name' => 'description',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::end_time => array(
            'name' => 'end_time',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::specification => array(
            'name' => 'specification',
            'repeated' => true,
            'type' => '\message\product\buyChainsSKU'
        ),
        self::specification_item => array(
            'name' => 'specification_item',
            'repeated' => true,
            'type' => '\message\product\SpecificationItem'
        ),
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::sub_name => array(
            'name' => 'sub_name',
            'required' => true,
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
        $this->values[self::buy_chains_id] = null;
        $this->values[self::images] = array();
        $this->values[self::name] = null;
        $this->values[self::description] = array();
        $this->values[self::end_time] = null;
        $this->values[self::specification] = array();
        $this->values[self::specification_item] = array();
        $this->values[self::product_id] = null;
        $this->values[self::sub_name] = null;
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
     * Appends value to 'description' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendDescription($value)
    {
        return $this->append(self::description, $value);
    }

    /**
     * Clears 'description' list
     *
     * @return null
     */
    public function clearDescription()
    {
        return $this->clear(self::description);
    }

    /**
     * Returns 'description' list
     *
     * @return string[]
     */
    public function getDescription()
    {
        return $this->get(self::description);
    }

    /**
     * Returns 'description' iterator
     *
     * @return \ArrayIterator
     */
    public function getDescriptionIterator()
    {
        return new \ArrayIterator($this->get(self::description));
    }

    /**
     * Returns element from 'description' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getDescriptionAt($offset)
    {
        return $this->get(self::description, $offset);
    }

    /**
     * Returns count of 'description' list
     *
     * @return int
     */
    public function getDescriptionCount()
    {
        return $this->count(self::description);
    }

    /**
     * Sets value of 'end_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEndTime($value)
    {
        return $this->set(self::end_time, $value);
    }

    /**
     * Returns value of 'end_time' property
     *
     * @return string
     */
    public function getEndTime()
    {
        $value = $this->get(self::end_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'specification' list
     *
     * @param \message\product\buyChainsSKU $value Value to append
     *
     * @return null
     */
    public function appendSpecification(\message\product\buyChainsSKU $value)
    {
        return $this->append(self::specification, $value);
    }

    /**
     * Clears 'specification' list
     *
     * @return null
     */
    public function clearSpecification()
    {
        return $this->clear(self::specification);
    }

    /**
     * Returns 'specification' list
     *
     * @return \message\product\buyChainsSKU[]
     */
    public function getSpecification()
    {
        return $this->get(self::specification);
    }

    /**
     * Returns 'specification' iterator
     *
     * @return \ArrayIterator
     */
    public function getSpecificationIterator()
    {
        return new \ArrayIterator($this->get(self::specification));
    }

    /**
     * Returns element from 'specification' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\buyChainsSKU
     */
    public function getSpecificationAt($offset)
    {
        return $this->get(self::specification, $offset);
    }

    /**
     * Returns count of 'specification' list
     *
     * @return int
     */
    public function getSpecificationCount()
    {
        return $this->count(self::specification);
    }

    /**
     * Appends value to 'specification_item' list
     *
     * @param \message\product\SpecificationItem $value Value to append
     *
     * @return null
     */
    public function appendSpecificationItem(\message\product\SpecificationItem $value)
    {
        return $this->append(self::specification_item, $value);
    }

    /**
     * Clears 'specification_item' list
     *
     * @return null
     */
    public function clearSpecificationItem()
    {
        return $this->clear(self::specification_item);
    }

    /**
     * Returns 'specification_item' list
     *
     * @return \message\product\SpecificationItem[]
     */
    public function getSpecificationItem()
    {
        return $this->get(self::specification_item);
    }

    /**
     * Returns 'specification_item' iterator
     *
     * @return \ArrayIterator
     */
    public function getSpecificationItemIterator()
    {
        return new \ArrayIterator($this->get(self::specification_item));
    }

    /**
     * Returns element from 'specification_item' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\SpecificationItem
     */
    public function getSpecificationItemAt($offset)
    {
        return $this->get(self::specification_item, $offset);
    }

    /**
     * Returns count of 'specification_item' list
     *
     * @return int
     */
    public function getSpecificationItemCount()
    {
        return $this->count(self::specification_item);
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
     * Sets value of 'sub_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSubName($value)
    {
        return $this->set(self::sub_name, $value);
    }

    /**
     * Returns value of 'sub_name' property
     *
     * @return string
     */
    public function getSubName()
    {
        $value = $this->get(self::sub_name);
        return $value === null ? (string)$value : $value;
    }
}