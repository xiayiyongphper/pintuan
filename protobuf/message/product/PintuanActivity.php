<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * PintuanActivity message
 */
class PintuanActivity extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const title = 2;
    const cover_picture = 3;
    const product_id = 4;
    const specification_id = 5;
    const wholesaler_id = 6;
    const pin_price = 7;
    const start_time = 8;
    const end_time = 9;
    const type = 10;
    const strategy = 11;
    const member_num = 12;
    const continue_pintuan = 13;
    const sort = 14;
    const create_at = 15;
    const update_at = 16;
    const del = 17;
    const has_pintuan = 18;
    const complete_member_num = 19;
    const colonel = 20;
    const already_pin = 21;
    const store_id = 22;
    const min_pin_price = 23;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::title => array(
            'name' => 'title',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::cover_picture => array(
            'name' => 'cover_picture',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::product_id => array(
            'name' => 'product_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::specification_id => array(
            'name' => 'specification_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::wholesaler_id => array(
            'name' => 'wholesaler_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pin_price => array(
            'name' => 'pin_price',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::start_time => array(
            'name' => 'start_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::end_time => array(
            'name' => 'end_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::strategy => array(
            'name' => 'strategy',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::member_num => array(
            'name' => 'member_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::continue_pintuan => array(
            'name' => 'continue_pintuan',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::sort => array(
            'name' => 'sort',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::update_at => array(
            'name' => 'update_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::del => array(
            'default' => 1,
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::has_pintuan => array(
            'name' => 'has_pintuan',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::complete_member_num => array(
            'name' => 'complete_member_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::colonel => array(
            'name' => 'colonel',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::already_pin => array(
            'name' => 'already_pin',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::min_pin_price => array(
            'name' => 'min_pin_price',
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
        $this->values[self::id] = null;
        $this->values[self::title] = null;
        $this->values[self::cover_picture] = null;
        $this->values[self::product_id] = null;
        $this->values[self::specification_id] = null;
        $this->values[self::wholesaler_id] = null;
        $this->values[self::pin_price] = null;
        $this->values[self::start_time] = null;
        $this->values[self::end_time] = null;
        $this->values[self::type] = null;
        $this->values[self::strategy] = null;
        $this->values[self::member_num] = null;
        $this->values[self::continue_pintuan] = null;
        $this->values[self::sort] = null;
        $this->values[self::create_at] = null;
        $this->values[self::update_at] = null;
        $this->values[self::del] = self::$fields[self::del]['default'];
        $this->values[self::has_pintuan] = null;
        $this->values[self::complete_member_num] = null;
        $this->values[self::colonel] = array();
        $this->values[self::already_pin] = null;
        $this->values[self::store_id] = null;
        $this->values[self::min_pin_price] = null;
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
     * Sets value of 'id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::id, $value);
    }

    /**
     * Returns value of 'id' property
     *
     * @return integer
     */
    public function getId()
    {
        $value = $this->get(self::id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'title' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTitle($value)
    {
        return $this->set(self::title, $value);
    }

    /**
     * Returns value of 'title' property
     *
     * @return string
     */
    public function getTitle()
    {
        $value = $this->get(self::title);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'cover_picture' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCoverPicture($value)
    {
        return $this->set(self::cover_picture, $value);
    }

    /**
     * Returns value of 'cover_picture' property
     *
     * @return string
     */
    public function getCoverPicture()
    {
        $value = $this->get(self::cover_picture);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'product_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProductId($value)
    {
        return $this->set(self::product_id, $value);
    }

    /**
     * Returns value of 'product_id' property
     *
     * @return integer
     */
    public function getProductId()
    {
        $value = $this->get(self::product_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'specification_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSpecificationId($value)
    {
        return $this->set(self::specification_id, $value);
    }

    /**
     * Returns value of 'specification_id' property
     *
     * @return integer
     */
    public function getSpecificationId()
    {
        $value = $this->get(self::specification_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'wholesaler_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setWholesalerId($value)
    {
        return $this->set(self::wholesaler_id, $value);
    }

    /**
     * Returns value of 'wholesaler_id' property
     *
     * @return integer
     */
    public function getWholesalerId()
    {
        $value = $this->get(self::wholesaler_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'pin_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPinPrice($value)
    {
        return $this->set(self::pin_price, $value);
    }

    /**
     * Returns value of 'pin_price' property
     *
     * @return string
     */
    public function getPinPrice()
    {
        $value = $this->get(self::pin_price);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'start_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStartTime($value)
    {
        return $this->set(self::start_time, $value);
    }

    /**
     * Returns value of 'start_time' property
     *
     * @return string
     */
    public function getStartTime()
    {
        $value = $this->get(self::start_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'end_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEndTime($value)
    {
        return $this->set(self::end_time, $value);
    }

    /**
     * Returns value of 'end_time' property
     *
     * @return string
     */
    public function getEndTime()
    {
        $value = $this->get(self::end_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::type, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'strategy' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStrategy($value)
    {
        return $this->set(self::strategy, $value);
    }

    /**
     * Returns value of 'strategy' property
     *
     * @return string
     */
    public function getStrategy()
    {
        $value = $this->get(self::strategy);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'member_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setMemberNum($value)
    {
        return $this->set(self::member_num, $value);
    }

    /**
     * Returns value of 'member_num' property
     *
     * @return integer
     */
    public function getMemberNum()
    {
        $value = $this->get(self::member_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'continue_pintuan' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setContinuePintuan($value)
    {
        return $this->set(self::continue_pintuan, $value);
    }

    /**
     * Returns value of 'continue_pintuan' property
     *
     * @return integer
     */
    public function getContinuePintuan()
    {
        $value = $this->get(self::continue_pintuan);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'sort' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setSort($value)
    {
        return $this->set(self::sort, $value);
    }

    /**
     * Returns value of 'sort' property
     *
     * @return integer
     */
    public function getSort()
    {
        $value = $this->get(self::sort);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'create_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCreateAt($value)
    {
        return $this->set(self::create_at, $value);
    }

    /**
     * Returns value of 'create_at' property
     *
     * @return string
     */
    public function getCreateAt()
    {
        $value = $this->get(self::create_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'update_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUpdateAt($value)
    {
        return $this->set(self::update_at, $value);
    }

    /**
     * Returns value of 'update_at' property
     *
     * @return string
     */
    public function getUpdateAt()
    {
        $value = $this->get(self::update_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'del' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDel($value)
    {
        return $this->set(self::del, $value);
    }

    /**
     * Returns value of 'del' property
     *
     * @return integer
     */
    public function getDel()
    {
        $value = $this->get(self::del);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'has_pintuan' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setHasPintuan($value)
    {
        return $this->set(self::has_pintuan, $value);
    }

    /**
     * Returns value of 'has_pintuan' property
     *
     * @return integer
     */
    public function getHasPintuan()
    {
        $value = $this->get(self::has_pintuan);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'complete_member_num' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCompleteMemberNum($value)
    {
        return $this->set(self::complete_member_num, $value);
    }

    /**
     * Returns value of 'complete_member_num' property
     *
     * @return integer
     */
    public function getCompleteMemberNum()
    {
        $value = $this->get(self::complete_member_num);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'colonel' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendColonel($value)
    {
        return $this->append(self::colonel, $value);
    }

    /**
     * Clears 'colonel' list
     *
     * @return null
     */
    public function clearColonel()
    {
        return $this->clear(self::colonel);
    }

    /**
     * Returns 'colonel' list
     *
     * @return string[]
     */
    public function getColonel()
    {
        return $this->get(self::colonel);
    }

    /**
     * Returns 'colonel' iterator
     *
     * @return \ArrayIterator
     */
    public function getColonelIterator()
    {
        return new \ArrayIterator($this->get(self::colonel));
    }

    /**
     * Returns element from 'colonel' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getColonelAt($offset)
    {
        return $this->get(self::colonel, $offset);
    }

    /**
     * Returns count of 'colonel' list
     *
     * @return int
     */
    public function getColonelCount()
    {
        return $this->count(self::colonel);
    }

    /**
     * Sets value of 'already_pin' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setAlreadyPin($value)
    {
        return $this->set(self::already_pin, $value);
    }

    /**
     * Returns value of 'already_pin' property
     *
     * @return integer
     */
    public function getAlreadyPin()
    {
        $value = $this->get(self::already_pin);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreId($value)
    {
        return $this->set(self::store_id, $value);
    }

    /**
     * Returns value of 'store_id' property
     *
     * @return integer
     */
    public function getStoreId()
    {
        $value = $this->get(self::store_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'min_pin_price' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMinPinPrice($value)
    {
        return $this->set(self::min_pin_price, $value);
    }

    /**
     * Returns value of 'min_pin_price' property
     *
     * @return string
     */
    public function getMinPinPrice()
    {
        $value = $this->get(self::min_pin_price);
        return $value === null ? (string)$value : $value;
    }
}