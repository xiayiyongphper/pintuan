<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * orderAlreadyPinReq message
 */
class orderAlreadyPinReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pintuan_id = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pintuan_id => array(
            'name' => 'pintuan_id',
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
        $this->values[self::pintuan_id] = array();
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
     * Appends value to 'pintuan_id' list
     *
     * @param integer $value Value to append
     *
     * @return null
     */
    public function appendPintuanId($value)
    {
        return $this->append(self::pintuan_id, $value);
    }

    /**
     * Clears 'pintuan_id' list
     *
     * @return null
     */
    public function clearPintuanId()
    {
        return $this->clear(self::pintuan_id);
    }

    /**
     * Returns 'pintuan_id' list
     *
     * @return integer[]
     */
    public function getPintuanId()
    {
        return $this->get(self::pintuan_id);
    }

    /**
     * Returns 'pintuan_id' iterator
     *
     * @return \ArrayIterator
     */
    public function getPintuanIdIterator()
    {
        return new \ArrayIterator($this->get(self::pintuan_id));
    }

    /**
     * Returns element from 'pintuan_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return integer
     */
    public function getPintuanIdAt($offset)
    {
        return $this->get(self::pintuan_id, $offset);
    }

    /**
     * Returns count of 'pintuan_id' list
     *
     * @return int
     */
    public function getPintuanIdCount()
    {
        return $this->count(self::pintuan_id);
    }
}