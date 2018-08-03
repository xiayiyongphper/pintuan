<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * OrderReviewRequest message
 */
class OrderReviewRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_id = 1;
    const items = 2;
    const coupon_id = 3;
    const include_new_user_product = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::items => array(
            'name' => 'items',
            'repeated' => true,
            'type' => '\message\order\OrderReviewReqItem'
        ),
        self::coupon_id => array(
            'name' => 'coupon_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::include_new_user_product => array(
            'name' => 'include_new_user_product',
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
        $this->values[self::user_id] = null;
        $this->values[self::items] = array();
        $this->values[self::coupon_id] = null;
        $this->values[self::include_new_user_product] = null;
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
     * Sets value of 'user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::user_id, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return integer
     */
    public function getUserId()
    {
        $value = $this->get(self::user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'items' list
     *
     * @param \message\order\OrderReviewReqItem $value Value to append
     *
     * @return null
     */
    public function appendItems(\message\order\OrderReviewReqItem $value)
    {
        return $this->append(self::items, $value);
    }

    /**
     * Clears 'items' list
     *
     * @return null
     */
    public function clearItems()
    {
        return $this->clear(self::items);
    }

    /**
     * Returns 'items' list
     *
     * @return \message\order\OrderReviewReqItem[]
     */
    public function getItems()
    {
        return $this->get(self::items);
    }

    /**
     * Returns 'items' iterator
     *
     * @return \ArrayIterator
     */
    public function getItemsIterator()
    {
        return new \ArrayIterator($this->get(self::items));
    }

    /**
     * Returns element from 'items' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\order\OrderReviewReqItem
     */
    public function getItemsAt($offset)
    {
        return $this->get(self::items, $offset);
    }

    /**
     * Returns count of 'items' list
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->count(self::items);
    }

    /**
     * Sets value of 'coupon_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCouponId($value)
    {
        return $this->set(self::coupon_id, $value);
    }

    /**
     * Returns value of 'coupon_id' property
     *
     * @return integer
     */
    public function getCouponId()
    {
        $value = $this->get(self::coupon_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'include_new_user_product' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIncludeNewUserProduct($value)
    {
        return $this->set(self::include_new_user_product, $value);
    }

    /**
     * Returns value of 'include_new_user_product' property
     *
     * @return integer
     */
    public function getIncludeNewUserProduct()
    {
        $value = $this->get(self::include_new_user_product);
        return $value === null ? (integer)$value : $value;
    }
}