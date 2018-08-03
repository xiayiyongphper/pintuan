<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * StoreRequest message
 */
class StoreRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const lat = 2;
    const lng = 3;
    const page = 4;
    const page_size = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::lat => array(
            'name' => 'lat',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::lng => array(
            'name' => 'lng',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::store_id] = array();
        $this->values[self::lat] = null;
        $this->values[self::lng] = null;
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
     * Appends value to 'store_id' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendStoreId($value)
    {
        return $this->append(self::store_id, $value);
    }

    /**
     * Clears 'store_id' list
     *
     * @return null
     */
    public function clearStoreId()
    {
        return $this->clear(self::store_id);
    }

    /**
     * Returns 'store_id' list
     *
     * @return integer[]
     */
    public function getStoreId()
    {
        return $this->get(self::store_id);
    }

    /**
     * Returns 'store_id' iterator
     *
     * @return \ArrayIterator
     */
    public function getStoreIdIterator()
    {
        return new \ArrayIterator($this->get(self::store_id));
    }

    /**
     * Returns element from 'store_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getStoreIdAt($offset)
    {
        return $this->get(self::store_id, $offset);
    }

    /**
     * Returns count of 'store_id' list
     *
     * @return int
     */
    public function getStoreIdCount()
    {
        return $this->count(self::store_id);
    }

    /**
     * Sets value of 'lat' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setLat($value)
    {
        return $this->set(self::lat, $value);
    }

    /**
     * Returns value of 'lat' property
     *
     * @return string
     */
    public function getLat()
    {
        $value = $this->get(self::lat);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'lng' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setLng($value)
    {
        return $this->set(self::lng, $value);
    }

    /**
     * Returns value of 'lng' property
     *
     * @return string
     */
    public function getLng()
    {
        $value = $this->get(self::lng);
        return $value === null ? (string)$value : $value;
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