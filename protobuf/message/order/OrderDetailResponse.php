<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderDetailResponse message
 */
class OrderDetailResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const order_info = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::order_info => array(
            'name' => 'order_info',
            'required' => false,
            'type' => '\message\order\orderDetailOriginal'
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
        $this->values[self::order_info] = null;
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
     * Sets value of 'order_info' property
     *
     * @param \message\order\orderDetailOriginal $value Property value
     *
     * @return null
     */
    public function setOrderInfo(\message\order\orderDetailOriginal $value=null)
    {
        return $this->set(self::order_info, $value);
    }

    /**
     * Returns value of 'order_info' property
     *
     * @return \message\order\orderDetailOriginal
     */
    public function getOrderInfo()
    {
        return $this->get(self::order_info);
    }
}