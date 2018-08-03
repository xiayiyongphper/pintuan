<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanChangeRes message
 */
class PintuanChangeRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const pintuan = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::pintuan => array(
            'name' => 'pintuan',
            'required' => true,
            'type' => '\message\product\Pintuan'
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
        $this->values[self::pintuan] = null;
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
     * Sets value of 'pintuan' property
     *
     * @param \message\product\Pintuan $value Property value
     *
     * @return null
     */
    public function setPintuan(\message\product\Pintuan $value=null)
    {
        return $this->set(self::pintuan, $value);
    }

    /**
     * Returns value of 'pintuan' property
     *
     * @return \message\product\Pintuan
     */
    public function getPintuan()
    {
        return $this->get(self::pintuan);
    }
}