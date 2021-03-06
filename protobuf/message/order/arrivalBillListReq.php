<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBillListReq message
 */
class arrivalBillListReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const arrival_code = 1;
    const start_date = 2;
    const end_date = 3;
    const pagination = 4;
    const store_id = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::arrival_code => array(
            'name' => 'arrival_code',
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
        self::pagination => array(
            'name' => 'pagination',
            'required' => false,
            'type' => '\message\common\Pagination'
        ),
        self::store_id => array(
            'name' => 'store_id',
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
        $this->values[self::arrival_code] = null;
        $this->values[self::start_date] = null;
        $this->values[self::end_date] = null;
        $this->values[self::pagination] = null;
        $this->values[self::store_id] = null;
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
     * Sets value of 'arrival_code' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setArrivalCode($value)
    {
        return $this->set(self::arrival_code, $value);
    }

    /**
     * Returns value of 'arrival_code' property
     *
     * @return string
     */
    public function getArrivalCode()
    {
        $value = $this->get(self::arrival_code);
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
}