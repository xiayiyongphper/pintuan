<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * BuyChainsListRes message
 */
class BuyChainsListRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const buy_chains = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::buy_chains => array(
            'name' => 'buy_chains',
            'repeated' => true,
            'type' => '\message\product\BuyChainsDetailRes'
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
        $this->values[self::buy_chains] = array();
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
     * Appends value to 'buy_chains' list
     *
     * @param \message\product\BuyChainsDetailRes $value Value to append
     *
     * @return null
     */
    public function appendBuyChains(\message\product\BuyChainsDetailRes $value)
    {
        return $this->append(self::buy_chains, $value);
    }

    /**
     * Clears 'buy_chains' list
     *
     * @return null
     */
    public function clearBuyChains()
    {
        return $this->clear(self::buy_chains);
    }

    /**
     * Returns 'buy_chains' list
     *
     * @return \message\product\BuyChainsDetailRes[]
     */
    public function getBuyChains()
    {
        return $this->get(self::buy_chains);
    }

    /**
     * Returns 'buy_chains' iterator
     *
     * @return \ArrayIterator
     */
    public function getBuyChainsIterator()
    {
        return new \ArrayIterator($this->get(self::buy_chains));
    }

    /**
     * Returns element from 'buy_chains' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\product\BuyChainsDetailRes
     */
    public function getBuyChainsAt($offset)
    {
        return $this->get(self::buy_chains, $offset);
    }

    /**
     * Returns count of 'buy_chains' list
     *
     * @return int
     */
    public function getBuyChainsCount()
    {
        return $this->count(self::buy_chains);
    }
}