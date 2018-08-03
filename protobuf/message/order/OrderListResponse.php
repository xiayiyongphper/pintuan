<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderListResponse message
 */
class OrderListResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order = 1;
    const pages = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order => array(
            'name' => 'order',
            'repeated' => true,
            'type' => '\message\common\Order'
        ),
        self::pages => array(
            'name' => 'pages',
            'required' => true,
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
        $this->values[self::order] = array();
        $this->values[self::pages] = null;
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
     * Appends value to 'order' list
     *
     * @param \message\common\Order $value Value to append
     *
     * @return null
     */
    public function appendOrder(\message\common\Order $value)
    {
        return $this->append(self::order, $value);
    }

    /**
     * Clears 'order' list
     *
     * @return null
     */
    public function clearOrder()
    {
        return $this->clear(self::order);
    }

    /**
     * Returns 'order' list
     *
     * @return \message\common\Order[]
     */
    public function getOrder()
    {
        return $this->get(self::order);
    }

    /**
     * Returns 'order' iterator
     *
     * @return \ArrayIterator
     */
    public function getOrderIterator()
    {
        return new \ArrayIterator($this->get(self::order));
    }

    /**
     * Returns element from 'order' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Order
     */
    public function getOrderAt($offset)
    {
        return $this->get(self::order, $offset);
    }

    /**
     * Returns count of 'order' list
     *
     * @return int
     */
    public function getOrderCount()
    {
        return $this->count(self::order);
    }

    /**
     * Sets value of 'pages' property
     *
     * @param \message\common\Pagination $value Property value
     *
     * @return null
     */
    public function setPages(\message\common\Pagination $value=null)
    {
        return $this->set(self::pages, $value);
    }

    /**
     * Returns value of 'pages' property
     *
     * @return \message\common\Pagination
     */
    public function getPages()
    {
        return $this->get(self::pages);
    }
}