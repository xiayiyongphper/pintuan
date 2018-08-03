<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanUserRes message
 */
class PintuanUserRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pintuan_user = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pintuan_user => array(
            'name' => 'pintuan_user',
            'repeated' => true,
            'type' => '\message\product\PintuanUser'
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
        $this->values[self::pintuan_user] = array();
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
     * Appends value to 'pintuan_user' list
     *
     * @param \message\product\PintuanUser $value Value to append
     *
     * @return null
     */
    public function appendPintuanUser(\message\product\PintuanUser $value)
    {
        return $this->append(self::pintuan_user, $value);
    }

    /**
     * Clears 'pintuan_user' list
     *
     * @return null
     */
    public function clearPintuanUser()
    {
        return $this->clear(self::pintuan_user);
    }

    /**
     * Returns 'pintuan_user' list
     *
     * @return \message\product\PintuanUser[]
     */
    public function getPintuanUser()
    {
        return $this->get(self::pintuan_user);
    }

    /**
     * Returns 'pintuan_user' iterator
     *
     * @return \ArrayIterator
     */
    public function getPintuanUserIterator()
    {
        return new \ArrayIterator($this->get(self::pintuan_user));
    }

    /**
     * Returns element from 'pintuan_user' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\PintuanUser
     */
    public function getPintuanUserAt($offset)
    {
        return $this->get(self::pintuan_user, $offset);
    }

    /**
     * Returns count of 'pintuan_user' list
     *
     * @return int
     */
    public function getPintuanUserCount()
    {
        return $this->count(self::pintuan_user);
    }
}