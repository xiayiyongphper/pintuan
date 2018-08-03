<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * getPromotionCouponResponse message
 */
class getPromotionCouponResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const coupon_list = 1;
    const type = 2;
    const background_img = 3;
    const background_color = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::coupon_list => array(
            'name' => 'coupon_list',
            'repeated' => true,
            'type' => '\message\common\Coupon'
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::background_img => array(
            'name' => 'background_img',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::background_color => array(
            'name' => 'background_color',
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
        $this->values[self::type] = null;
        $this->values[self::background_img] = null;
        $this->values[self::background_color] = null;
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
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'background_img' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBackgroundImg($value)
    {
        return $this->set(self::background_img, $value);
    }

    /**
     * Returns value of 'background_img' property
     *
     * @return string
     */
    public function getBackgroundImg()
    {
        $value = $this->get(self::background_img);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'background_color' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBackgroundColor($value)
    {
        return $this->set(self::background_color, $value);
    }

    /**
     * Returns value of 'background_color' property
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        $value = $this->get(self::background_color);
        return $value === null ? (string)$value : $value;
    }
}