<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * ProductDetailReq message
 */
class ProductDetailReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_id = 1;
    const activity_id = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_id => array(
            'name' => 'product_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::activity_id => array(
            'name' => 'activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
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
        $this->values[self::product_id] = null;
        $this->values[self::activity_id] = null;
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
     * Sets value of 'activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setActivityId($value)
    {
        return $this->set(self::activity_id, $value);
    }

    /**
     * Returns value of 'activity_id' property
     *
     * @return integer
     */
    public function getActivityId()
    {
        $value = $this->get(self::activity_id);
        return $value === null ? (integer)$value : $value;
    }
}