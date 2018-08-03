<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * Items message
 */
class Items extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const items = 1;
    const include_new_user_product = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::items => array(
            'name' => 'items',
            'repeated' => true,
            'type' => '\message\product\Item'
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
        $this->values[self::items] = array();
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
     * Appends value to 'items' list
     *
     * @param \message\product\Item $value Value to append
     *
     * @return null
     */
    public function appendItems(\message\product\Item $value)
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
     * @return \message\product\Item[]
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
     * @return \message\product\Item
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