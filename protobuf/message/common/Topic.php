<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Topic message
 */
class Topic extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const title = 2;
    const img_url = 3;
    const type = 4;
    const products = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::title => array(
            'name' => 'title',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::img_url => array(
            'name' => 'img_url',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::products => array(
            'name' => 'products',
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
        $this->values[self::title] = null;
        $this->values[self::img_url] = null;
        $this->values[self::type] = null;
        $this->values[self::products] = null;
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
     * Sets value of 'title' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTitle($value)
    {
        return $this->set(self::title, $value);
    }

    /**
     * Returns value of 'title' property
     *
     * @return string
     */
    public function getTitle()
    {
        $value = $this->get(self::title);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'img_url' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImgUrl($value)
    {
        return $this->set(self::img_url, $value);
    }

    /**
     * Returns value of 'img_url' property
     *
     * @return string
     */
    public function getImgUrl()
    {
        $value = $this->get(self::img_url);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'products' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProducts($value)
    {
        return $this->set(self::products, $value);
    }

    /**
     * Returns value of 'products' property
     *
     * @return string
     */
    public function getProducts()
    {
        $value = $this->get(self::products);
        return $value === null ? (string)$value : $value;
    }
}