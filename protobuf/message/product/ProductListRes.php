<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * ProductListRes message
 */
class ProductListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const product_list = 1;
    const pages = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::product_list => array(
            'name' => 'product_list',
            'repeated' => true,
            'type' => '\message\product\ProductBreif'
        ),
        self::pages => array(
            'name' => 'pages',
            'required' => true,
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
        $this->values[self::product_list] = array();
        $this->values[self::pages] = null;
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
     * Appends value to 'product_list' list
     *
     * @param \message\product\ProductBreif $value Value to append
     *
     * @return null
     */
    public function appendProductList(\message\product\ProductBreif $value)
    {
        return $this->append(self::product_list, $value);
    }

    /**
     * Clears 'product_list' list
     *
     * @return null
     */
    public function clearProductList()
    {
        return $this->clear(self::product_list);
    }

    /**
     * Returns 'product_list' list
     *
     * @return \message\product\ProductBreif[]
     */
    public function getProductList()
    {
        return $this->get(self::product_list);
    }

    /**
     * Returns 'product_list' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductListIterator()
    {
        return new \ArrayIterator($this->get(self::product_list));
    }

    /**
     * Returns element from 'product_list' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\ProductBreif
     */
    public function getProductListAt($offset)
    {
        return $this->get(self::product_list, $offset);
    }

    /**
     * Returns count of 'product_list' list
     *
     * @return int
     */
    public function getProductListCount()
    {
        return $this->count(self::product_list);
    }

    /**
     * Sets value of 'pages' property
     *
     * @param \message\common\Pagination $value Property value
     *
     * @return null
     */
    public function setPages(\message\common\Pagination $value=null)
    {
        return $this->set(self::pages, $value);
    }

    /**
     * Returns value of 'pages' property
     *
     * @return \message\common\Pagination
     */
    public function getPages()
    {
        return $this->get(self::pages);
    }
}