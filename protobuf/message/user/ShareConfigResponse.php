<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * ShareConfigResponse message
 */
class ShareConfigResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const share_configs = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::share_configs => array(
            'name' => 'share_configs',
            'repeated' => true,
            'type' => '\message\common\ShareConfig'
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
        $this->values[self::share_configs] = array();
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
     * Appends value to 'share_configs' list
     *
     * @param \message\common\ShareConfig $value Value to append
     *
     * @return null
     */
    public function appendShareConfigs(\message\common\ShareConfig $value)
    {
        return $this->append(self::share_configs, $value);
    }

    /**
     * Clears 'share_configs' list
     *
     * @return null
     */
    public function clearShareConfigs()
    {
        return $this->clear(self::share_configs);
    }

    /**
     * Returns 'share_configs' list
     *
     * @return \message\common\ShareConfig[]
     */
    public function getShareConfigs()
    {
        return $this->get(self::share_configs);
    }

    /**
     * Returns 'share_configs' iterator
     *
     * @return \ArrayIterator
     */
    public function getShareConfigsIterator()
    {
        return new \ArrayIterator($this->get(self::share_configs));
    }

    /**
     * Returns element from 'share_configs' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\ShareConfig
     */
    public function getShareConfigsAt($offset)
    {
        return $this->get(self::share_configs, $offset);
    }

    /**
     * Returns count of 'share_configs' list
     *
     * @return int
     */
    public function getShareConfigsCount()
    {
        return $this->count(self::share_configs);
    }
}