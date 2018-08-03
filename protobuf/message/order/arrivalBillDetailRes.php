<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * arrivalBillDetailRes message
 */
class arrivalBillDetailRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const arrival_bill = 1;
    const arrival_bill_detail = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::arrival_bill => array(
            'name' => 'arrival_bill',
            'required' => false,
            'type' => '\message\order\arrivalBill'
        ),
        self::arrival_bill_detail => array(
            'name' => 'arrival_bill_detail',
            'repeated' => true,
            'type' => '\message\order\arrivalBillDetail'
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
        $this->values[self::arrival_bill] = null;
        $this->values[self::arrival_bill_detail] = array();
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
     * Sets value of 'arrival_bill' property
     *
     * @param \message\order\arrivalBill $value Property value
     *
     * @return null
     */
    public function setArrivalBill(\message\order\arrivalBill $value=null)
    {
        return $this->set(self::arrival_bill, $value);
    }

    /**
     * Returns value of 'arrival_bill' property
     *
     * @return \message\order\arrivalBill
     */
    public function getArrivalBill()
    {
        return $this->get(self::arrival_bill);
    }

    /**
     * Appends value to 'arrival_bill_detail' list
     *
     * @param \message\order\arrivalBillDetail $value Value to append
     *
     * @return null
     */
    public function appendArrivalBillDetail(\message\order\arrivalBillDetail $value)
    {
        return $this->append(self::arrival_bill_detail, $value);
    }

    /**
     * Clears 'arrival_bill_detail' list
     *
     * @return null
     */
    public function clearArrivalBillDetail()
    {
        return $this->clear(self::arrival_bill_detail);
    }

    /**
     * Returns 'arrival_bill_detail' list
     *
     * @return \message\order\arrivalBillDetail[]
     */
    public function getArrivalBillDetail()
    {
        return $this->get(self::arrival_bill_detail);
    }

    /**
     * Returns 'arrival_bill_detail' iterator
     *
     * @return \ArrayIterator
     */
    public function getArrivalBillDetailIterator()
    {
        return new \ArrayIterator($this->get(self::arrival_bill_detail));
    }

    /**
     * Returns element from 'arrival_bill_detail' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\arrivalBillDetail
     */
    public function getArrivalBillDetailAt($offset)
    {
        return $this->get(self::arrival_bill_detail, $offset);
    }

    /**
     * Returns count of 'arrival_bill_detail' list
     *
     * @return int
     */
    public function getArrivalBillDetailCount()
    {
        return $this->count(self::arrival_bill_detail);
    }
}