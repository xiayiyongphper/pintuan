<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WholesalerResponse message
 */
class WholesalerResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const wholesalers = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::wholesalers => array(
            'name' => 'wholesalers',
            'repeated' => true,
            'type' => '\message\store\Wholesaler'
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
        $this->values[self::wholesalers] = array();
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
     * Appends value to 'wholesalers' list
     *
     * @param \message\store\Wholesaler $value Value to append
     *
     * @return null
     */
    public function appendWholesalers(\message\store\Wholesaler $value)
    {
        return $this->append(self::wholesalers, $value);
    }

    /**
     * Clears 'wholesalers' list
     *
     * @return null
     */
    public function clearWholesalers()
    {
        return $this->clear(self::wholesalers);
    }

    /**
     * Returns 'wholesalers' list
     *
     * @return \message\store\Wholesaler[]
     */
    public function getWholesalers()
    {
        return $this->get(self::wholesalers);
    }

    /**
     * Returns 'wholesalers' iterator
     *
     * @return \ArrayIterator
     */
    public function getWholesalersIterator()
    {
        return new \ArrayIterator($this->get(self::wholesalers));
    }

    /**
     * Returns element from 'wholesalers' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\store\Wholesaler
     */
    public function getWholesalersAt($offset)
    {
        return $this->get(self::wholesalers, $offset);
    }

    /**
     * Returns count of 'wholesalers' list
     *
     * @return int
     */
    public function getWholesalersCount()
    {
        return $this->count(self::wholesalers);
    }
}