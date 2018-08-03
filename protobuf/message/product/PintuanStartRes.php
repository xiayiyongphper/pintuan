<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanStartRes message
 */
class PintuanStartRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const activity = 1;
    const pintuan = 2;
    const product_picture = 3;
    const product_name = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::activity => array(
            'name' => 'activity',
            'required' => false,
            'type' => '\message\product\PintuanActivity'
        ),
        self::pintuan => array(
            'name' => 'pintuan',
            'required' => false,
            'type' => '\message\product\Pintuan'
        ),
        self::product_picture => array(
            'name' => 'product_picture',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_name => array(
            'name' => 'product_name',
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
        $this->values[self::activity] = null;
        $this->values[self::pintuan] = null;
        $this->values[self::product_picture] = array();
        $this->values[self::product_name] = null;
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
     * Sets value of 'activity' property
     *
     * @param \message\product\PintuanActivity $value Property value
     *
     * @return null
     */
    public function setActivity(\message\product\PintuanActivity $value=null)
    {
        return $this->set(self::activity, $value);
    }

    /**
     * Returns value of 'activity' property
     *
     * @return \message\product\PintuanActivity
     */
    public function getActivity()
    {
        return $this->get(self::activity);
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

    /**
     * Appends value to 'product_picture' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendProductPicture($value)
    {
        return $this->append(self::product_picture, $value);
    }

    /**
     * Clears 'product_picture' list
     *
     * @return null
     */
    public function clearProductPicture()
    {
        return $this->clear(self::product_picture);
    }

    /**
     * Returns 'product_picture' list
     *
     * @return string[]
     */
    public function getProductPicture()
    {
        return $this->get(self::product_picture);
    }

    /**
     * Returns 'product_picture' iterator
     *
     * @return \ArrayIterator
     */
    public function getProductPictureIterator()
    {
        return new \ArrayIterator($this->get(self::product_picture));
    }

    /**
     * Returns element from 'product_picture' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getProductPictureAt($offset)
    {
        return $this->get(self::product_picture, $offset);
    }

    /**
     * Returns count of 'product_picture' list
     *
     * @return int
     */
    public function getProductPictureCount()
    {
        return $this->count(self::product_picture);
    }

    /**
     * Sets value of 'product_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProductName($value)
    {
        return $this->set(self::product_name, $value);
    }

    /**
     * Returns value of 'product_name' property
     *
     * @return string
     */
    public function getProductName()
    {
        $value = $this->get(self::product_name);
        return $value === null ? (string)$value : $value;
    }
}