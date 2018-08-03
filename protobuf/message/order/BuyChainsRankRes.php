<?php
/**
 *
 * message.order package
 */

namespace message\order;
/**
 * BuyChainsRankRes message
 */
class BuyChainsRankRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const rank = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::rank => array(
            'name' => 'rank',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::rank] = null;
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
     * Sets value of 'rank' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRank($value)
    {
        return $this->set(self::rank, $value);
    }

    /**
     * Returns value of 'rank' property
     *
     * @return string
     */
    public function getRank()
    {
        $value = $this->get(self::rank);
        return $value === null ? (string)$value : $value;
    }
}