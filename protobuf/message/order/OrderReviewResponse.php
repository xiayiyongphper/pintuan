<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderReviewResponse message
 */
class OrderReviewResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const amount = 1;
    const discount_amount = 2;
    const available_coupons = 3;
    const unavailable_coupons = 4;
    const payment_amount = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::amount => array(
            'name' => 'amount',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::discount_amount => array(
            'name' => 'discount_amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::available_coupons => array(
            'name' => 'available_coupons',
            'repeated' => true,
            'type' => '\message\common\Coupon'
        ),
        self::unavailable_coupons => array(
            'name' => 'unavailable_coupons',
            'repeated' => true,
            'type' => '\message\common\Coupon'
        ),
        self::payment_amount => array(
            'name' => 'payment_amount',
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
        $this->values[self::amount] = null;
        $this->values[self::discount_amount] = null;
        $this->values[self::available_coupons] = array();
        $this->values[self::unavailable_coupons] = array();
        $this->values[self::payment_amount] = null;
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
     * Sets value of 'amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAmount($value)
    {
        return $this->set(self::amount, $value);
    }

    /**
     * Returns value of 'amount' property
     *
     * @return string
     */
    public function getAmount()
    {
        $value = $this->get(self::amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'discount_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDiscountAmount($value)
    {
        return $this->set(self::discount_amount, $value);
    }

    /**
     * Returns value of 'discount_amount' property
     *
     * @return string
     */
    public function getDiscountAmount()
    {
        $value = $this->get(self::discount_amount);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Appends value to 'available_coupons' list
     *
     * @param \message\common\Coupon $value Value to append
     *
     * @return null
     */
    public function appendAvailableCoupons(\message\common\Coupon $value)
    {
        return $this->append(self::available_coupons, $value);
    }

    /**
     * Clears 'available_coupons' list
     *
     * @return null
     */
    public function clearAvailableCoupons()
    {
        return $this->clear(self::available_coupons);
    }

    /**
     * Returns 'available_coupons' list
     *
     * @return \message\common\Coupon[]
     */
    public function getAvailableCoupons()
    {
        return $this->get(self::available_coupons);
    }

    /**
     * Returns 'available_coupons' iterator
     *
     * @return \ArrayIterator
     */
    public function getAvailableCouponsIterator()
    {
        return new \ArrayIterator($this->get(self::available_coupons));
    }

    /**
     * Returns element from 'available_coupons' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Coupon
     */
    public function getAvailableCouponsAt($offset)
    {
        return $this->get(self::available_coupons, $offset);
    }

    /**
     * Returns count of 'available_coupons' list
     *
     * @return int
     */
    public function getAvailableCouponsCount()
    {
        return $this->count(self::available_coupons);
    }

    /**
     * Appends value to 'unavailable_coupons' list
     *
     * @param \message\common\Coupon $value Value to append
     *
     * @return null
     */
    public function appendUnavailableCoupons(\message\common\Coupon $value)
    {
        return $this->append(self::unavailable_coupons, $value);
    }

    /**
     * Clears 'unavailable_coupons' list
     *
     * @return null
     */
    public function clearUnavailableCoupons()
    {
        return $this->clear(self::unavailable_coupons);
    }

    /**
     * Returns 'unavailable_coupons' list
     *
     * @return \message\common\Coupon[]
     */
    public function getUnavailableCoupons()
    {
        return $this->get(self::unavailable_coupons);
    }

    /**
     * Returns 'unavailable_coupons' iterator
     *
     * @return \ArrayIterator
     */
    public function getUnavailableCouponsIterator()
    {
        return new \ArrayIterator($this->get(self::unavailable_coupons));
    }

    /**
     * Returns element from 'unavailable_coupons' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Coupon
     */
    public function getUnavailableCouponsAt($offset)
    {
        return $this->get(self::unavailable_coupons, $offset);
    }

    /**
     * Returns count of 'unavailable_coupons' list
     *
     * @return int
     */
    public function getUnavailableCouponsCount()
    {
        return $this->count(self::unavailable_coupons);
    }

    /**
     * Sets value of 'payment_amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPaymentAmount($value)
    {
        return $this->set(self::payment_amount, $value);
    }

    /**
     * Returns value of 'payment_amount' property
     *
     * @return string
     */
    public function getPaymentAmount()
    {
        $value = $this->get(self::payment_amount);
        return $value === null ? (string)$value : $value;
    }
}