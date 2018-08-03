<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * HomeResponse message
 */
class HomeResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const banners = 1;
    const topics = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::banners => array(
            'name' => 'banners',
            'repeated' => true,
            'type' => '\message\common\Banner'
        ),
        self::topics => array(
            'name' => 'topics',
            'repeated' => true,
            'type' => '\message\common\Topic'
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
        $this->values[self::banners] = array();
        $this->values[self::topics] = array();
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
     * Appends value to 'banners' list
     *
     * @param \message\common\Banner $value Value to append
     *
     * @return null
     */
    public function appendBanners(\message\common\Banner $value)
    {
        return $this->append(self::banners, $value);
    }

    /**
     * Clears 'banners' list
     *
     * @return null
     */
    public function clearBanners()
    {
        return $this->clear(self::banners);
    }

    /**
     * Returns 'banners' list
     *
     * @return \message\common\Banner[]
     */
    public function getBanners()
    {
        return $this->get(self::banners);
    }

    /**
     * Returns 'banners' iterator
     *
     * @return \ArrayIterator
     */
    public function getBannersIterator()
    {
        return new \ArrayIterator($this->get(self::banners));
    }

    /**
     * Returns element from 'banners' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Banner
     */
    public function getBannersAt($offset)
    {
        return $this->get(self::banners, $offset);
    }

    /**
     * Returns count of 'banners' list
     *
     * @return int
     */
    public function getBannersCount()
    {
        return $this->count(self::banners);
    }

    /**
     * Appends value to 'topics' list
     *
     * @param \message\common\Topic $value Value to append
     *
     * @return null
     */
    public function appendTopics(\message\common\Topic $value)
    {
        return $this->append(self::topics, $value);
    }

    /**
     * Clears 'topics' list
     *
     * @return null
     */
    public function clearTopics()
    {
        return $this->clear(self::topics);
    }

    /**
     * Returns 'topics' list
     *
     * @return \message\common\Topic[]
     */
    public function getTopics()
    {
        return $this->get(self::topics);
    }

    /**
     * Returns 'topics' iterator
     *
     * @return \ArrayIterator
     */
    public function getTopicsIterator()
    {
        return new \ArrayIterator($this->get(self::topics));
    }

    /**
     * Returns element from 'topics' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\common\Topic
     */
    public function getTopicsAt($offset)
    {
        return $this->get(self::topics, $offset);
    }

    /**
     * Returns count of 'topics' list
     *
     * @return int
     */
    public function getTopicsCount()
    {
        return $this->count(self::topics);
    }
}