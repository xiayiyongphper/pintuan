<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanProductDetailRes message
 */
class PintuanProductDetailRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const activity = 1;
    const product_picture = 2;
    const product_name = 3;
    const specification = 4;
    const product_price = 5;
    const product_detail = 6;
    const pintuan = 7;
    const min_price = 8;
    const specifications = 9;
    const specification_item = 10;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::activity => array(
            'name' => 'activity',
            'required' => true,
            'type' => '\message\product\PintuanActivity'
        ),
        self::product_picture => array(
            'name' => 'product_picture',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_name => array(
            'name' => 'product_name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::specification => array(
            'name' => 'specification',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_price => array(
            'name' => 'product_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_detail => array(
            'name' => 'product_detail',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan => array(
            'name' => 'pintuan',
            'repeated' => true,
            'type' => '\message\product\Pintuan'
        ),
        self::min_price => array(
            'name' => 'min_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::specifications => array(
            'name' => 'specifications',
            'repeated' => true,
            'type' => '\message\product\Specification'
        ),
        self::specification_item => array(
            'name' => 'specification_item',
            'repeated' => true,
            'type' => '\message\product\SpecificationItem'
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
        $this->values[self::activity] = null;
        $this->values[self::product_picture] = array();
        $this->values[self::product_name] = null;
        $this->values[self::specification] = null;
        $this->values[self::product_price] = null;
        $this->values[self::product_detail] = array();
        $this->values[self::pintuan] = array();
        $this->values[self::min_price] = null;
        $this->values[self::specifications] = array();
        $this->values[self::specification_item] = array();
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
     * Sets value of 'activity' property
     *
     * @param \message\product\PintuanActivity $value Property value
     *
     * @return null
     */
    public function setActivity(\message\product\PintuanActivity $value=null)
    {
        return $this->set(self::activity, $value);
    }

    /**
     * Returns value of 'activity' property
     *
     * @return \message\product\PintuanActivity
     */
    public function getActivity()
    {
        return $this->get(self::activity);
    }

    /**
     * Appends value to 'product_picture' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendProductPicture($value)
    {
        return $this->append(self::product_picture, $value);
    }

    /**
     * Clears 'product_picture' list
     *
     * @return null
     */
    public function clearProductPicture()
    {
        return $this->clear(self::product_picture);
    }

    /**
     * Returns 'product_picture' list
     *
     * @return string[]
     */
    public function getProductPicture()
    {
        return $this->get(self::product_picture);
    }

    /**
     * Returns 'product_picture' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductPictureIterator()
    {
        return new \ArrayIterator($this->get(self::product_picture));
    }

    /**
     * Returns element from 'product_picture' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getProductPictureAt($offset)
    {
        return $this->get(self::product_picture, $offset);
    }

    /**
     * Returns count of 'product_picture' list
     *
     * @return int
     */
    public function getProductPictureCount()
    {
        return $this->count(self::product_picture);
    }

    /**
     * Sets value of 'product_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProductName($value)
    {
        return $this->set(self::product_name, $value);
    }

    /**
     * Returns value of 'product_name' property
     *
     * @return string
     */
    public function getProductName()
    {
        $value = $this->get(self::product_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'specification' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSpecification($value)
    {
        return $this->set(self::specification, $value);
    }

    /**
     * Returns value of 'specification' property
     *
     * @return string
     */
    public function getSpecification()
    {
        $value = $this->get(self::specification);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'product_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProductPrice($value)
    {
        return $this->set(self::product_price, $value);
    }

    /**
     * Returns value of 'product_price' property
     *
     * @return string
     */
    public function getProductPrice()
    {
        $value = $this->get(self::product_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'product_detail' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendProductDetail($value)
    {
        return $this->append(self::product_detail, $value);
    }

    /**
     * Clears 'product_detail' list
     *
     * @return null
     */
    public function clearProductDetail()
    {
        return $this->clear(self::product_detail);
    }

    /**
     * Returns 'product_detail' list
     *
     * @return string[]
     */
    public function getProductDetail()
    {
        return $this->get(self::product_detail);
    }

    /**
     * Returns 'product_detail' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductDetailIterator()
    {
        return new \ArrayIterator($this->get(self::product_detail));
    }

    /**
     * Returns element from 'product_detail' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getProductDetailAt($offset)
    {
        return $this->get(self::product_detail, $offset);
    }

    /**
     * Returns count of 'product_detail' list
     *
     * @return int
     */
    public function getProductDetailCount()
    {
        return $this->count(self::product_detail);
    }

    /**
     * Appends value to 'pintuan' list
     *
     * @param \message\product\Pintuan $value Value to append
     *
     * @return null
     */
    public function appendPintuan(\message\product\Pintuan $value)
    {
        return $this->append(self::pintuan, $value);
    }

    /**
     * Clears 'pintuan' list
     *
     * @return null
     */
    public function clearPintuan()
    {
        return $this->clear(self::pintuan);
    }

    /**
     * Returns 'pintuan' list
     *
     * @return \message\product\Pintuan[]
     */
    public function getPintuan()
    {
        return $this->get(self::pintuan);
    }

    /**
     * Returns 'pintuan' iterator
     *
     * @return \ArrayIterator
     */
    public function getPintuanIterator()
    {
        return new \ArrayIterator($this->get(self::pintuan));
    }

    /**
     * Returns element from 'pintuan' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\Pintuan
     */
    public function getPintuanAt($offset)
    {
        return $this->get(self::pintuan, $offset);
    }

    /**
     * Returns count of 'pintuan' list
     *
     * @return int
     */
    public function getPintuanCount()
    {
        return $this->count(self::pintuan);
    }

    /**
     * Sets value of 'min_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMinPrice($value)
    {
        return $this->set(self::min_price, $value);
    }

    /**
     * Returns value of 'min_price' property
     *
     * @return string
     */
    public function getMinPrice()
    {
        $value = $this->get(self::min_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'specifications' list
     *
     * @param \message\product\Specification $value Value to append
     *
     * @return null
     */
    public function appendSpecifications(\message\product\Specification $value)
    {
        return $this->append(self::specifications, $value);
    }

    /**
     * Clears 'specifications' list
     *
     * @return null
     */
    public function clearSpecifications()
    {
        return $this->clear(self::specifications);
    }

    /**
     * Returns 'specifications' list
     *
     * @return \message\product\Specification[]
     */
    public function getSpecifications()
    {
        return $this->get(self::specifications);
    }

    /**
     * Returns 'specifications' iterator
     *
     * @return \ArrayIterator
     */
    public function getSpecificationsIterator()
    {
        return new \ArrayIterator($this->get(self::specifications));
    }

    /**
     * Returns element from 'specifications' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\Specification
     */
    public function getSpecificationsAt($offset)
    {
        return $this->get(self::specifications, $offset);
    }

    /**
     * Returns count of 'specifications' list
     *
     * @return int
     */
    public function getSpecificationsCount()
    {
        return $this->count(self::specifications);
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
}