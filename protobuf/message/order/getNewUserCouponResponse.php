<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * getNewUserCouponResponse message
 */
class getNewUserCouponResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const coupon_list = 1;
    const discount_total = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::coupon_list => array(
            'name' => 'coupon_list',
            'repeated' => true,
            'type' => '\message\common\Coupon'
        ),
        self::discount_total => array(
            'name' => 'discount_total',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::coupon_list] = array();
        $this->values[self::discount_total] = null;
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
     * Appends value to 'coupon_list' list
     *
     * @param \message\common\Coupon $value Value to append
     *
     * @return null
     */
    public function appendCouponList(\message\common\Coupon $value)
    {
        return $this->append(self::coupon_list, $value);
    }

    /**
     * Clears 'coupon_list' list
     *
     * @return null
     */
    public function clearCouponList()
    {
        return $this->clear(self::coupon_list);
    }

    /**
     * Returns 'coupon_list' list
     *
     * @return \message\common\Coupon[]
     */
    public function getCouponList()
    {
        return $this->get(self::coupon_list);
    }

    /**
     * Returns 'coupon_list' iterator
     *
     * @return \ArrayIterator
     */
    public function getCouponListIterator()
    {
        return new \ArrayIterator($this->get(self::coupon_list));
    }

    /**
     * Returns element from 'coupon_list' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Coupon
     */
    public function getCouponListAt($offset)
    {
        return $this->get(self::coupon_list, $offset);
    }

    /**
     * Returns count of 'coupon_list' list
     *
     * @return int
     */
    public function getCouponListCount()
    {
        return $this->count(self::coupon_list);
    }

    /**
     * Sets value of 'discount_total' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDiscountTotal($value)
    {
        return $this->set(self::discount_total, $value);
    }

    /**
     * Returns value of 'discount_total' property
     *
     * @return string
     */
    public function getDiscountTotal()
    {
        $value = $this->get(self::discount_total);
        return $value === null ? (string)$value : $value;
    }
}