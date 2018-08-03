<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * CheckPintuanActivityRequest message
 */
class CheckPintuanActivityRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pintuan_activity_id = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => true,
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
        $this->values[self::pintuan_activity_id] = null;
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
     * Sets value of 'pintuan_activity_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanActivityId($value)
    {
        return $this->set(self::pintuan_activity_id, $value);
    }

    /**
     * Returns value of 'pintuan_activity_id' property
     *
     * @return integer
     */
    public function getPintuanActivityId()
    {
        $value = $this->get(self::pintuan_activity_id);
        return $value === null ? (integer)$value : $value;
    }
}