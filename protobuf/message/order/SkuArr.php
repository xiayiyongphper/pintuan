<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * SkuArr message
 */
class SkuArr extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const sku_id = 1;
    const sku_name = 2;
    const arrival_num = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::sku_id => array(
            'name' => 'sku_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::sku_name => array(
            'name' => 'sku_name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::arrival_num => array(
            'name' => 'arrival_num',
            'required' => true,
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
        $this->values[self::sku_id] = null;
        $this->values[self::sku_name] = null;
        $this->values[self::arrival_num] = null;
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
}