<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * createArrivalBillSaveReq message
 */
class createArrivalBillSaveReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const sku_arr = 1;
    const store_id = 2;
    const remark = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::sku_arr => array(
            'name' => 'sku_arr',
            'repeated' => true,
            'type' => '\message\order\arrivalBillDetail'
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::remark => array(
            'name' => 'remark',
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
        $this->values[self::sku_arr] = array();
        $this->values[self::store_id] = null;
        $this->values[self::remark] = null;
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
     * Appends value to 'sku_arr' list
     *
     * @param \message\order\arrivalBillDetail $value Value to append
     *
     * @return null
     */
    public function appendSkuArr(\message\order\arrivalBillDetail $value)
    {
        return $this->append(self::sku_arr, $value);
    }

    /**
     * Clears 'sku_arr' list
     *
     * @return null
     */
    public function clearSkuArr()
    {
        return $this->clear(self::sku_arr);
    }

    /**
     * Returns 'sku_arr' list
     *
     * @return \message\order\arrivalBillDetail[]
     */
    public function getSkuArr()
    {
        return $this->get(self::sku_arr);
    }

    /**
     * Returns 'sku_arr' iterator
     *
     * @return \ArrayIterator
     */
    public function getSkuArrIterator()
    {
        return new \ArrayIterator($this->get(self::sku_arr));
    }

    /**
     * Returns element from 'sku_arr' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\arrivalBillDetail
     */
    public function getSkuArrAt($offset)
    {
        return $this->get(self::sku_arr, $offset);
    }

    /**
     * Returns count of 'sku_arr' list
     *
     * @return int
     */
    public function getSkuArrCount()
    {
        return $this->count(self::sku_arr);
    }

    /**
     * Sets value of 'store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreId($value)
    {
        return $this->set(self::store_id, $value);
    }

    /**
     * Returns value of 'store_id' property
     *
     * @return integer
     */
    public function getStoreId()
    {
        $value = $this->get(self::store_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'remark' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRemark($value)
    {
        return $this->set(self::remark, $value);
    }

    /**
     * Returns value of 'remark' property
     *
     * @return string
     */
    public function getRemark()
    {
        $value = $this->get(self::remark);
        return $value === null ? (string)$value : $value;
    }
}