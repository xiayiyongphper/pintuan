<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalOrderListRes message
 */
class arrivalOrderListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_info = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_info => array(
            'name' => 'order_info',
            'repeated' => true,
            'type' => '\message\order\order'
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
        $this->values[self::order_info] = array();
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
     * Appends value to 'order_info' list
     *
     * @param \message\order\order $value Value to append
     *
     * @return null
     */
    public function appendOrderInfo(\message\order\order $value)
    {
        return $this->append(self::order_info, $value);
    }

    /**
     * Clears 'order_info' list
     *
     * @return null
     */
    public function clearOrderInfo()
    {
        return $this->clear(self::order_info);
    }

    /**
     * Returns 'order_info' list
     *
     * @return \message\order\order[]
     */
    public function getOrderInfo()
    {
        return $this->get(self::order_info);
    }

    /**
     * Returns 'order_info' iterator
     *
     * @return \ArrayIterator
     */
    public function getOrderInfoIterator()
    {
        return new \ArrayIterator($this->get(self::order_info));
    }

    /**
     * Returns element from 'order_info' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\order
     */
    public function getOrderInfoAt($offset)
    {
        return $this->get(self::order_info, $offset);
    }

    /**
     * Returns count of 'order_info' list
     *
     * @return int
     */
    public function getOrderInfoCount()
    {
        return $this->count(self::order_info);
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