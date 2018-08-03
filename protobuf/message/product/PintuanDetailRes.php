<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanDetailRes message
 */
class PintuanDetailRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const activity = 1;
    const product_picture = 2;
    const product_name = 3;
    const specification = 4;
    const product_price = 5;
    const product_detail = 6;
    const pintuan = 7;
    const else_pintuan = 8;
    const pintuan_status = 9;
    const pintuan_info = 10;
    const time_status = 11;
    const specifications = 12;
    const specification_item = 13;
    const pintuan_end_time = 14;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::activity => array(
            'name' => 'activity',
            'required' => false,
            'type' => '\message\product\PintuanActivity'
        ),
        self::product_picture => array(
            'name' => 'product_picture',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_name => array(
            'name' => 'product_name',
            'required' => false,
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
        self::else_pintuan => array(
            'name' => 'else_pintuan',
            'repeated' => true,
            'type' => '\message\product\Pintuan'
        ),
        self::pintuan_status => array(
            'name' => 'pintuan_status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_info => array(
            'name' => 'pintuan_info',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::time_status => array(
            'name' => 'time_status',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
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
        self::pintuan_end_time => array(
            'name' => 'pintuan_end_time',
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
        $this->values[self::activity] = null;
        $this->values[self::product_picture] = array();
        $this->values[self::product_name] = null;
        $this->values[self::specification] = null;
        $this->values[self::product_price] = null;
        $this->values[self::product_detail] = array();
        $this->values[self::pintuan] = array();
        $this->values[self::else_pintuan] = array();
        $this->values[self::pintuan_status] = null;
        $this->values[self::pintuan_info] = null;
        $this->values[self::time_status] = null;
        $this->values[self::specifications] = array();
        $this->values[self::specification_item] = array();
        $this->values[self::pintuan_end_time] = null;
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
     * Appends value to 'else_pintuan' list
     *
     * @param \message\product\Pintuan $value Value to append
     *
     * @return null
     */
    public function appendElsePintuan(\message\product\Pintuan $value)
    {
        return $this->append(self::else_pintuan, $value);
    }

    /**
     * Clears 'else_pintuan' list
     *
     * @return null
     */
    public function clearElsePintuan()
    {
        return $this->clear(self::else_pintuan);
    }

    /**
     * Returns 'else_pintuan' list
     *
     * @return \message\product\Pintuan[]
     */
    public function getElsePintuan()
    {
        return $this->get(self::else_pintuan);
    }

    /**
     * Returns 'else_pintuan' iterator
     *
     * @return \ArrayIterator
     */
    public function getElsePintuanIterator()
    {
        return new \ArrayIterator($this->get(self::else_pintuan));
    }

    /**
     * Returns element from 'else_pintuan' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\Pintuan
     */
    public function getElsePintuanAt($offset)
    {
        return $this->get(self::else_pintuan, $offset);
    }

    /**
     * Returns count of 'else_pintuan' list
     *
     * @return int
     */
    public function getElsePintuanCount()
    {
        return $this->count(self::else_pintuan);
    }

    /**
     * Sets value of 'pintuan_status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanStatus($value)
    {
        return $this->set(self::pintuan_status, $value);
    }

    /**
     * Returns value of 'pintuan_status' property
     *
     * @return integer
     */
    public function getPintuanStatus()
    {
        $value = $this->get(self::pintuan_status);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pintuan_info' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanInfo($value)
    {
        return $this->set(self::pintuan_info, $value);
    }

    /**
     * Returns value of 'pintuan_info' property
     *
     * @return integer
     */
    public function getPintuanInfo()
    {
        $value = $this->get(self::pintuan_info);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'time_status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setTimeStatus($value)
    {
        return $this->set(self::time_status, $value);
    }

    /**
     * Returns value of 'time_status' property
     *
     * @return integer
     */
    public function getTimeStatus()
    {
        $value = $this->get(self::time_status);
        return $value === null ? (integer)$value : $value;
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

    /**
     * Sets value of 'pintuan_end_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPintuanEndTime($value)
    {
        return $this->set(self::pintuan_end_time, $value);
    }

    /**
     * Returns value of 'pintuan_end_time' property
     *
     * @return string
     */
    public function getPintuanEndTime()
    {
        $value = $this->get(self::pintuan_end_time);
        return $value === null ? (string)$value : $value;
    }
}