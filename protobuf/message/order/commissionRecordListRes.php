<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * commissionRecordListRes message
 */
class commissionRecordListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const commission_info = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::commission_info => array(
            'name' => 'commission_info',
            'repeated' => true,
            'type' => '\message\order\commissionRecord'
        ),
        self::pagination => array(
            'name' => 'pagination',
            'required' => false,
            'type' => '\message\common\Pagination'
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
        $this->values[self::commission_info] = array();
        $this->values[self::pagination] = null;
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
     * Appends value to 'commission_info' list
     *
     * @param \message\order\commissionRecord $value Value to append
     *
     * @return null
     */
    public function appendCommissionInfo(\message\order\commissionRecord $value)
    {
        return $this->append(self::commission_info, $value);
    }

    /**
     * Clears 'commission_info' list
     *
     * @return null
     */
    public function clearCommissionInfo()
    {
        return $this->clear(self::commission_info);
    }

    /**
     * Returns 'commission_info' list
     *
     * @return \message\order\commissionRecord[]
     */
    public function getCommissionInfo()
    {
        return $this->get(self::commission_info);
    }

    /**
     * Returns 'commission_info' iterator
     *
     * @return \ArrayIterator
     */
    public function getCommissionInfoIterator()
    {
        return new \ArrayIterator($this->get(self::commission_info));
    }

    /**
     * Returns element from 'commission_info' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\commissionRecord
     */
    public function getCommissionInfoAt($offset)
    {
        return $this->get(self::commission_info, $offset);
    }

    /**
     * Returns count of 'commission_info' list
     *
     * @return int
     */
    public function getCommissionInfoCount()
    {
        return $this->count(self::commission_info);
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
}