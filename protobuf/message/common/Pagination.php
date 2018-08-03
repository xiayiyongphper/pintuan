<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Pagination message
 */
class Pagination extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const total_count = 1;
    const page = 2;
    const last_page = 3;
    const page_size = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::total_count => array(
            'name' => 'total_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::page => array(
            'name' => 'page',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::last_page => array(
            'name' => 'last_page',
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
        $this->values[self::total_count] = null;
        $this->values[self::page] = null;
        $this->values[self::last_page] = null;
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
     * Sets value of 'total_count' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setTotalCount($value)
    {
        return $this->set(self::total_count, $value);
    }

    /**
     * Returns value of 'total_count' property
     *
     * @return integer
     */
    public function getTotalCount()
    {
        $value = $this->get(self::total_count);
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
     * Sets value of 'last_page' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setLastPage($value)
    {
        return $this->set(self::last_page, $value);
    }

    /**
     * Returns value of 'last_page' property
     *
     * @return integer
     */
    public function getLastPage()
    {
        $value = $this->get(self::last_page);
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