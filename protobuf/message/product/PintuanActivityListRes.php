<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanActivityListRes message
 */
class PintuanActivityListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const activity = 1;
    const pages = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::activity => array(
            'name' => 'activity',
            'repeated' => true,
            'type' => '\message\product\PintuanActivity'
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
        $this->values[self::activity] = array();
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
     * Appends value to 'activity' list
     *
     * @param \message\product\PintuanActivity $value Value to append
     *
     * @return null
     */
    public function appendActivity(\message\product\PintuanActivity $value)
    {
        return $this->append(self::activity, $value);
    }

    /**
     * Clears 'activity' list
     *
     * @return null
     */
    public function clearActivity()
    {
        return $this->clear(self::activity);
    }

    /**
     * Returns 'activity' list
     *
     * @return \message\product\PintuanActivity[]
     */
    public function getActivity()
    {
        return $this->get(self::activity);
    }

    /**
     * Returns 'activity' iterator
     *
     * @return \ArrayIterator
     */
    public function getActivityIterator()
    {
        return new \ArrayIterator($this->get(self::activity));
    }

    /**
     * Returns element from 'activity' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\PintuanActivity
     */
    public function getActivityAt($offset)
    {
        return $this->get(self::activity, $offset);
    }

    /**
     * Returns count of 'activity' list
     *
     * @return int
     */
    public function getActivityCount()
    {
        return $this->count(self::activity);
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