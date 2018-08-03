<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * ProductBreif message
 */
class ProductBreif extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const name = 2;
    const min_price = 3;
    const image = 4;
    const spec_id = 5;
    const new_price = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::name => array(
            'name' => 'name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::min_price => array(
            'name' => 'min_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::image => array(
            'name' => 'image',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::spec_id => array(
            'name' => 'spec_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::new_price => array(
            'name' => 'new_price',
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
        $this->values[self::id] = null;
        $this->values[self::name] = null;
        $this->values[self::min_price] = null;
        $this->values[self::image] = null;
        $this->values[self::spec_id] = null;
        $this->values[self::new_price] = null;
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
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::name, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        $value = $this->get(self::name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'min_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMinPrice($value)
    {
        return $this->set(self::min_price, $value);
    }

    /**
     * Returns value of 'min_price' property
     *
     * @return string
     */
    public function getMinPrice()
    {
        $value = $this->get(self::min_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'image' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImage($value)
    {
        return $this->set(self::image, $value);
    }

    /**
     * Returns value of 'image' property
     *
     * @return string
     */
    public function getImage()
    {
        $value = $this->get(self::image);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'spec_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSpecId($value)
    {
        return $this->set(self::spec_id, $value);
    }

    /**
     * Returns value of 'spec_id' property
     *
     * @return integer
     */
    public function getSpecId()
    {
        $value = $this->get(self::spec_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'new_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNewPrice($value)
    {
        return $this->set(self::new_price, $value);
    }

    /**
     * Returns value of 'new_price' property
     *
     * @return string
     */
    public function getNewPrice()
    {
        $value = $this->get(self::new_price);
        return $value === null ? (string)$value : $value;
    }
}