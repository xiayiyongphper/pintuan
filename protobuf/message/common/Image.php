<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Image message
 */
class Image extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const src = 1;
    const href = 2;
    const height = 3;
    const tag_param = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::src => array(
            'name' => 'src',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::href => array(
            'name' => 'href',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::height => array(
            'name' => 'height',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::tag_param => array(
            'name' => 'tag_param',
            'required' => false,
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
        $this->values[self::src] = null;
        $this->values[self::href] = null;
        $this->values[self::height] = null;
        $this->values[self::tag_param] = null;
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
     * Sets value of 'src' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSrc($value)
    {
        return $this->set(self::src, $value);
    }

    /**
     * Returns value of 'src' property
     *
     * @return string
     */
    public function getSrc()
    {
        $value = $this->get(self::src);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'href' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setHref($value)
    {
        return $this->set(self::href, $value);
    }

    /**
     * Returns value of 'href' property
     *
     * @return string
     */
    public function getHref()
    {
        $value = $this->get(self::href);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'height' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setHeight($value)
    {
        return $this->set(self::height, $value);
    }

    /**
     * Returns value of 'height' property
     *
     * @return integer
     */
    public function getHeight()
    {
        $value = $this->get(self::height);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'tag_param' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTagParam($value)
    {
        return $this->set(self::tag_param, $value);
    }

    /**
     * Returns value of 'tag_param' property
     *
     * @return string
     */
    public function getTagParam()
    {
        $value = $this->get(self::tag_param);
        return $value === null ? (string)$value : $value;
    }
}