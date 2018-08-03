<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * StoreResponse message
 */
class StoreResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const stores = 1;
    const pages = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::stores => array(
            'name' => 'stores',
            'repeated' => true,
            'type' => '\message\store\Store'
        ),
        self::pages => array(
            'name' => 'pages',
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
        $this->values[self::stores] = array();
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
     * Appends value to 'stores' list
     *
     * @param \message\store\Store $value Value to append
     *
     * @return null
     */
    public function appendStores(\message\store\Store $value)
    {
        return $this->append(self::stores, $value);
    }

    /**
     * Clears 'stores' list
     *
     * @return null
     */
    public function clearStores()
    {
        return $this->clear(self::stores);
    }

    /**
     * Returns 'stores' list
     *
     * @return \message\store\Store[]
     */
    public function getStores()
    {
        return $this->get(self::stores);
    }

    /**
     * Returns 'stores' iterator
     *
     * @return \ArrayIterator
     */
    public function getStoresIterator()
    {
        return new \ArrayIterator($this->get(self::stores));
    }

    /**
     * Returns element from 'stores' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\store\Store
     */
    public function getStoresAt($offset)
    {
        return $this->get(self::stores, $offset);
    }

    /**
     * Returns count of 'stores' list
     *
     * @return int
     */
    public function getStoresCount()
    {
        return $this->count(self::stores);
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