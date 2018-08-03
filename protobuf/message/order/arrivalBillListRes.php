<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBillListRes message
 */
class arrivalBillListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const arrival_bill = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::arrival_bill => array(
            'name' => 'arrival_bill',
            'repeated' => true,
            'type' => '\message\order\arrivalBill'
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
        $this->values[self::arrival_bill] = array();
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
     * Appends value to 'arrival_bill' list
     *
     * @param \message\order\arrivalBill $value Value to append
     *
     * @return null
     */
    public function appendArrivalBill(\message\order\arrivalBill $value)
    {
        return $this->append(self::arrival_bill, $value);
    }

    /**
     * Clears 'arrival_bill' list
     *
     * @return null
     */
    public function clearArrivalBill()
    {
        return $this->clear(self::arrival_bill);
    }

    /**
     * Returns 'arrival_bill' list
     *
     * @return \message\order\arrivalBill[]
     */
    public function getArrivalBill()
    {
        return $this->get(self::arrival_bill);
    }

    /**
     * Returns 'arrival_bill' iterator
     *
     * @return \ArrayIterator
     */
    public function getArrivalBillIterator()
    {
        return new \ArrayIterator($this->get(self::arrival_bill));
    }

    /**
     * Returns element from 'arrival_bill' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\arrivalBill
     */
    public function getArrivalBillAt($offset)
    {
        return $this->get(self::arrival_bill, $offset);
    }

    /**
     * Returns count of 'arrival_bill' list
     *
     * @return int
     */
    public function getArrivalBillCount()
    {
        return $this->count(self::arrival_bill);
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