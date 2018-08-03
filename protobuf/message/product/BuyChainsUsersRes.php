<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * BuyChainsUsersRes message
 */
class BuyChainsUsersRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const list = 1;
    const pagination = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::list => array(
            'name' => 'list',
            'repeated' => true,
            'type' => '\message\product\BuyChainsUser'
        ),
        self::pagination => array(
            'name' => 'pagination',
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
        $this->values[self::list] = array();
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
     * Appends value to 'list' list
     *
     * @param \message\product\BuyChainsUser $value Value to append
     *
     * @return null
     */
    public function appendList(\message\product\BuyChainsUser $value)
    {
        return $this->append(self::list, $value);
    }

    /**
     * Clears 'list' list
     *
     * @return null
     */
    public function clearList()
    {
        return $this->clear(self::list);
    }

    /**
     * Returns 'list' list
     *
     * @return \message\product\BuyChainsUser[]
     */
    public function getList()
    {
        return $this->get(self::list);
    }

    /**
     * Returns 'list' iterator
     *
     * @return \ArrayIterator
     */
    public function getListIterator()
    {
        return new \ArrayIterator($this->get(self::list));
    }

    /**
     * Returns element from 'list' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\BuyChainsUser
     */
    public function getListAt($offset)
    {
        return $this->get(self::list, $offset);
    }

    /**
     * Returns count of 'list' list
     *
     * @return int
     */
    public function getListCount()
    {
        return $this->count(self::list);
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