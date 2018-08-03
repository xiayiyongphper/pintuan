<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * CategoryTree message
 */
class CategoryTree extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const name = 2;
    const parent_id = 3;
    const level = 4;
    const img = 5;
    const child_nodes = 6;

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
        self::parent_id => array(
            'name' => 'parent_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::level => array(
            'name' => 'level',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::img => array(
            'name' => 'img',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::child_nodes => array(
            'name' => 'child_nodes',
            'required' => false,
            'type' => '\message\product\CategoryTree'
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
        $this->values[self::parent_id] = null;
        $this->values[self::level] = null;
        $this->values[self::img] = null;
        $this->values[self::child_nodes] = null;
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
     * Sets value of 'parent_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setParentId($value)
    {
        return $this->set(self::parent_id, $value);
    }

    /**
     * Returns value of 'parent_id' property
     *
     * @return integer
     */
    public function getParentId()
    {
        $value = $this->get(self::parent_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'level' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setLevel($value)
    {
        return $this->set(self::level, $value);
    }

    /**
     * Returns value of 'level' property
     *
     * @return integer
     */
    public function getLevel()
    {
        $value = $this->get(self::level);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'img' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImg($value)
    {
        return $this->set(self::img, $value);
    }

    /**
     * Returns value of 'img' property
     *
     * @return string
     */
    public function getImg()
    {
        $value = $this->get(self::img);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'child_nodes' property
     *
     * @param \message\product\CategoryTree $value Property value
     *
     * @return null
     */
    public function setChildNodes(\message\product\CategoryTree $value=null)
    {
        return $this->set(self::child_nodes, $value);
    }

    /**
     * Returns value of 'child_nodes' property
     *
     * @return \message\product\CategoryTree
     */
    public function getChildNodes()
    {
        return $this->get(self::child_nodes);
    }
}