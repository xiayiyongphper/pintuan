<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * CategoryRes message
 */
class CategoryRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const category_list = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::category_list => array(
            'name' => 'category_list',
            'repeated' => true,
            'type' => '\message\product\Category'
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
        $this->values[self::category_list] = array();
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
     * Appends value to 'category_list' list
     *
     * @param \message\product\Category $value Value to append
     *
     * @return null
     */
    public function appendCategoryList(\message\product\Category $value)
    {
        return $this->append(self::category_list, $value);
    }

    /**
     * Clears 'category_list' list
     *
     * @return null
     */
    public function clearCategoryList()
    {
        return $this->clear(self::category_list);
    }

    /**
     * Returns 'category_list' list
     *
     * @return \message\product\Category[]
     */
    public function getCategoryList()
    {
        return $this->get(self::category_list);
    }

    /**
     * Returns 'category_list' iterator
     *
     * @return \ArrayIterator
     */
    public function getCategoryListIterator()
    {
        return new \ArrayIterator($this->get(self::category_list));
    }

    /**
     * Returns element from 'category_list' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\Category
     */
    public function getCategoryListAt($offset)
    {
        return $this->get(self::category_list, $offset);
    }

    /**
     * Returns count of 'category_list' list
     *
     * @return int
     */
    public function getCategoryListCount()
    {
        return $this->count(self::category_list);
    }
}