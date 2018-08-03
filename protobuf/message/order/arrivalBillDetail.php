<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBillDetail message
 */
class arrivalBillDetail extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const arrival_bill_id = 2;
    const sku_id = 3;
    const sku_name = 4;
    const arrival_num = 5;
    const order_arr = 6;
    const order_num = 7;
    const should_arrival_num = 8;
    const images = 9;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::arrival_bill_id => array(
            'name' => 'arrival_bill_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::sku_id => array(
            'name' => 'sku_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::sku_name => array(
            'name' => 'sku_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::arrival_num => array(
            'name' => 'arrival_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::order_arr => array(
            'name' => 'order_arr',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::order_num => array(
            'name' => 'order_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::should_arrival_num => array(
            'name' => 'should_arrival_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::images => array(
            'name' => 'images',
            'repeated' => true,
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
        $this->values[self::id] = null;
        $this->values[self::arrival_bill_id] = null;
        $this->values[self::sku_id] = null;
        $this->values[self::sku_name] = null;
        $this->values[self::arrival_num] = null;
        $this->values[self::order_arr] = null;
        $this->values[self::order_num] = null;
        $this->values[self::should_arrival_num] = null;
        $this->values[self::images] = array();
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
     * Sets value of 'arrival_bill_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setArrivalBillId($value)
    {
        return $this->set(self::arrival_bill_id, $value);
    }

    /**
     * Returns value of 'arrival_bill_id' property
     *
     * @return integer
     */
    public function getArrivalBillId()
    {
        $value = $this->get(self::arrival_bill_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'sku_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSkuId($value)
    {
        return $this->set(self::sku_id, $value);
    }

    /**
     * Returns value of 'sku_id' property
     *
     * @return integer
     */
    public function getSkuId()
    {
        $value = $this->get(self::sku_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'sku_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSkuName($value)
    {
        return $this->set(self::sku_name, $value);
    }

    /**
     * Returns value of 'sku_name' property
     *
     * @return string
     */
    public function getSkuName()
    {
        $value = $this->get(self::sku_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'arrival_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setArrivalNum($value)
    {
        return $this->set(self::arrival_num, $value);
    }

    /**
     * Returns value of 'arrival_num' property
     *
     * @return integer
     */
    public function getArrivalNum()
    {
        $value = $this->get(self::arrival_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'order_arr' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOrderArr($value)
    {
        return $this->set(self::order_arr, $value);
    }

    /**
     * Returns value of 'order_arr' property
     *
     * @return string
     */
    public function getOrderArr()
    {
        $value = $this->get(self::order_arr);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'order_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOrderNum($value)
    {
        return $this->set(self::order_num, $value);
    }

    /**
     * Returns value of 'order_num' property
     *
     * @return integer
     */
    public function getOrderNum()
    {
        $value = $this->get(self::order_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'should_arrival_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setShouldArrivalNum($value)
    {
        return $this->set(self::should_arrival_num, $value);
    }

    /**
     * Returns value of 'should_arrival_num' property
     *
     * @return integer
     */
    public function getShouldArrivalNum()
    {
        $value = $this->get(self::should_arrival_num);
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
}