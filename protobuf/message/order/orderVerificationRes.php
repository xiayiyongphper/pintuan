<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderVerificationRes message
 */
class orderVerificationRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order = 1;
    const order_product = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order => array(
            'name' => 'order',
            'required' => true,
            'type' => '\message\order\order'
        ),
        self::order_product => array(
            'name' => 'order_product',
            'repeated' => true,
            'type' => '\message\order\orderProduct'
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
        $this->values[self::order] = null;
        $this->values[self::order_product] = array();
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
     * Sets value of 'order' property
     *
     * @param \message\order\order $value Property value
     *
     * @return null
     */
    public function setOrder(\message\order\order $value=null)
    {
        return $this->set(self::order, $value);
    }

    /**
     * Returns value of 'order' property
     *
     * @return \message\order\order
     */
    public function getOrder()
    {
        return $this->get(self::order);
    }

    /**
     * Appends value to 'order_product' list
     *
     * @param \message\order\orderProduct $value Value to append
     *
     * @return null
     */
    public function appendOrderProduct(\message\order\orderProduct $value)
    {
        return $this->append(self::order_product, $value);
    }

    /**
     * Clears 'order_product' list
     *
     * @return null
     */
    public function clearOrderProduct()
    {
        return $this->clear(self::order_product);
    }

    /**
     * Returns 'order_product' list
     *
     * @return \message\order\orderProduct[]
     */
    public function getOrderProduct()
    {
        return $this->get(self::order_product);
    }

    /**
     * Returns 'order_product' iterator
     *
     * @return \ArrayIterator
     */
    public function getOrderProductIterator()
    {
        return new \ArrayIterator($this->get(self::order_product));
    }

    /**
     * Returns element from 'order_product' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\orderProduct
     */
    public function getOrderProductAt($offset)
    {
        return $this->get(self::order_product, $offset);
    }

    /**
     * Returns count of 'order_product' list
     *
     * @return int
     */
    public function getOrderProductCount()
    {
        return $this->count(self::order_product);
    }
}