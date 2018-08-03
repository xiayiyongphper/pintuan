<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserStore message
 */
class UserStore extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_store_id = 1;
    const name = 2;
    const phone = 3;
    const store_id = 4;
    const default_store = 5;
    const has_order = 6;
    const del = 7;
    const user_id = 8;
    const page = 9;
    const page_size = 10;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_store_id => array(
            'name' => 'user_store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::name => array(
            'name' => 'name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::phone => array(
            'name' => 'phone',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::default_store => array(
            'name' => 'default_store',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::has_order => array(
            'name' => 'has_order',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::del => array(
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::page => array(
            'name' => 'page',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::page_size => array(
            'name' => 'page_size',
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
        $this->values[self::user_store_id] = null;
        $this->values[self::name] = null;
        $this->values[self::phone] = null;
        $this->values[self::store_id] = null;
        $this->values[self::default_store] = null;
        $this->values[self::has_order] = null;
        $this->values[self::del] = null;
        $this->values[self::user_id] = null;
        $this->values[self::page] = null;
        $this->values[self::page_size] = null;
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
     * Sets value of 'user_store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserStoreId($value)
    {
        return $this->set(self::user_store_id, $value);
    }

    /**
     * Returns value of 'user_store_id' property
     *
     * @return integer
     */
    public function getUserStoreId()
    {
        $value = $this->get(self::user_store_id);
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
     * Sets value of 'phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPhone($value)
    {
        return $this->set(self::phone, $value);
    }

    /**
     * Returns value of 'phone' property
     *
     * @return string
     */
    public function getPhone()
    {
        $value = $this->get(self::phone);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreId($value)
    {
        return $this->set(self::store_id, $value);
    }

    /**
     * Returns value of 'store_id' property
     *
     * @return integer
     */
    public function getStoreId()
    {
        $value = $this->get(self::store_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'default_store' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDefaultStore($value)
    {
        return $this->set(self::default_store, $value);
    }

    /**
     * Returns value of 'default_store' property
     *
     * @return integer
     */
    public function getDefaultStore()
    {
        $value = $this->get(self::default_store);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'has_order' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setHasOrder($value)
    {
        return $this->set(self::has_order, $value);
    }

    /**
     * Returns value of 'has_order' property
     *
     * @return integer
     */
    public function getHasOrder()
    {
        $value = $this->get(self::has_order);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'del' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDel($value)
    {
        return $this->set(self::del, $value);
    }

    /**
     * Returns value of 'del' property
     *
     * @return integer
     */
    public function getDel()
    {
        $value = $this->get(self::del);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::user_id, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return integer
     */
    public function getUserId()
    {
        $value = $this->get(self::user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'page' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPage($value)
    {
        return $this->set(self::page, $value);
    }

    /**
     * Returns value of 'page' property
     *
     * @return integer
     */
    public function getPage()
    {
        $value = $this->get(self::page);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'page_size' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPageSize($value)
    {
        return $this->set(self::page_size, $value);
    }

    /**
     * Returns value of 'page_size' property
     *
     * @return integer
     */
    public function getPageSize()
    {
        $value = $this->get(self::page_size);
        return $value === null ? (integer)$value : $value;
    }
}