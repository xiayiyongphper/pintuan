<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserListRes message
 */
class UserListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const users = 1;
    const pages = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::users => array(
            'name' => 'users',
            'repeated' => true,
            'type' => '\message\user\UserBreif'
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
        $this->values[self::users] = array();
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
     * Appends value to 'users' list
     *
     * @param \message\user\UserBreif $value Value to append
     *
     * @return null
     */
    public function appendUsers(\message\user\UserBreif $value)
    {
        return $this->append(self::users, $value);
    }

    /**
     * Clears 'users' list
     *
     * @return null
     */
    public function clearUsers()
    {
        return $this->clear(self::users);
    }

    /**
     * Returns 'users' list
     *
     * @return \message\user\UserBreif[]
     */
    public function getUsers()
    {
        return $this->get(self::users);
    }

    /**
     * Returns 'users' iterator
     *
     * @return \ArrayIterator
     */
    public function getUsersIterator()
    {
        return new \ArrayIterator($this->get(self::users));
    }

    /**
     * Returns element from 'users' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\user\UserBreif
     */
    public function getUsersAt($offset)
    {
        return $this->get(self::users, $offset);
    }

    /**
     * Returns count of 'users' list
     *
     * @return int
     */
    public function getUsersCount()
    {
        return $this->count(self::users);
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