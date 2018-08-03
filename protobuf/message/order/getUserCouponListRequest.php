<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * getUserCouponListRequest message
 */
class getUserCouponListRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_id = 1;
    const list_type = 2;
    const page = 3;
    const page_size = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::list_type => array(
            'name' => 'list_type',
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
        $this->values[self::user_id] = null;
        $this->values[self::list_type] = null;
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
     * Sets value of 'list_type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setListType($value)
    {
        return $this->set(self::list_type, $value);
    }

    /**
     * Returns value of 'list_type' property
     *
     * @return integer
     */
    public function getListType()
    {
        $value = $this->get(self::list_type);
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