<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * createArrivalBillListRes message
 */
class createArrivalBillListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const sku_arr = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::sku_arr => array(
            'name' => 'sku_arr',
            'repeated' => true,
            'type' => '\message\order\arrivalBillDetail'
        ),
        self::pagination => array(
            'name' => 'pagination',
            'required' => false,
            'type' => '\message\common\Pagination'
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
        $this->values[self::pagination] = null;
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
     * Sets value of 'pagination' property
     *
     * @param \message\common\Pagination $value Property value
     *
     * @return null
     */
    public function setPagination(\message\common\Pagination $value=null)
    {
        return $this->set(self::pagination, $value);
    }

    /**
     * Returns value of 'pagination' property
     *
     * @return \message\common\Pagination
     */
    public function getPagination()
    {
        return $this->get(self::pagination);
    }
}