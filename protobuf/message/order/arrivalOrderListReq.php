<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalOrderListReq message
 */
class arrivalOrderListReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const search_all = 2;
    const start_date = 3;
    const end_date = 4;
    const status = 5;
    const pagination = 6;
    const arrival_id = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::search_all => array(
            'name' => 'search_all',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::start_date => array(
            'name' => 'start_date',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::end_date => array(
            'name' => 'end_date',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pagination => array(
            'name' => 'pagination',
            'required' => false,
            'type' => '\message\common\Pagination'
        ),
        self::arrival_id => array(
            'name' => 'arrival_id',
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
        $this->values[self::store_id] = null;
        $this->values[self::search_all] = null;
        $this->values[self::start_date] = null;
        $this->values[self::end_date] = null;
        $this->values[self::status] = null;
        $this->values[self::pagination] = null;
        $this->values[self::arrival_id] = null;
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
     * Sets value of 'search_all' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSearchAll($value)
    {
        return $this->set(self::search_all, $value);
    }

    /**
     * Returns value of 'search_all' property
     *
     * @return string
     */
    public function getSearchAll()
    {
        $value = $this->get(self::search_all);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'start_date' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStartDate($value)
    {
        return $this->set(self::start_date, $value);
    }

    /**
     * Returns value of 'start_date' property
     *
     * @return string
     */
    public function getStartDate()
    {
        $value = $this->get(self::start_date);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'end_date' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEndDate($value)
    {
        return $this->set(self::end_date, $value);
    }

    /**
     * Returns value of 'end_date' property
     *
     * @return string
     */
    public function getEndDate()
    {
        $value = $this->get(self::end_date);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStatus($value)
    {
        return $this->set(self::status, $value);
    }

    /**
     * Returns value of 'status' property
     *
     * @return integer
     */
    public function getStatus()
    {
        $value = $this->get(self::status);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pagination' property
     *
     * @param \message\common\Pagination $value Property value
     *
     * @return null
     */
    public function setPagination(\message\common\Pagination $value=null)
    {
        return $this->set(self::pagination, $value);
    }

    /**
     * Returns value of 'pagination' property
     *
     * @return \message\common\Pagination
     */
    public function getPagination()
    {
        return $this->get(self::pagination);
    }

    /**
     * Sets value of 'arrival_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setArrivalId($value)
    {
        return $this->set(self::arrival_id, $value);
    }

    /**
     * Returns value of 'arrival_id' property
     *
     * @return integer
     */
    public function getArrivalId()
    {
        $value = $this->get(self::arrival_id);
        return $value === null ? (integer)$value : $value;
    }
}