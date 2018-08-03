<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserListReq message
 */
class UserListReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_ids = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_ids => array(
            'name' => 'user_ids',
            'repeated' => true,
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
        $this->values[self::user_ids] = array();
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
     * Appends value to 'user_ids' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendUserIds($value)
    {
        return $this->append(self::user_ids, $value);
    }

    /**
     * Clears 'user_ids' list
     *
     * @return null
     */
    public function clearUserIds()
    {
        return $this->clear(self::user_ids);
    }

    /**
     * Returns 'user_ids' list
     *
     * @return integer[]
     */
    public function getUserIds()
    {
        return $this->get(self::user_ids);
    }

    /**
     * Returns 'user_ids' iterator
     *
     * @return \ArrayIterator
     */
    public function getUserIdsIterator()
    {
        return new \ArrayIterator($this->get(self::user_ids));
    }

    /**
     * Returns element from 'user_ids' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getUserIdsAt($offset)
    {
        return $this->get(self::user_ids, $offset);
    }

    /**
     * Returns count of 'user_ids' list
     *
     * @return int
     */
    public function getUserIdsCount()
    {
        return $this->count(self::user_ids);
    }
}