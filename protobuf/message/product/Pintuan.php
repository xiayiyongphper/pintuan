<?php
/**
 *
 * message.product package
 */

namespace message\product;
/**
 * Pintuan message
 */
class Pintuan extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const id = 1;
    const pintuan_activity_id = 2;
    const create_user_id = 3;
    const avatar_url = 4;
    const nick_name = 5;
    const pintuan_info = 6;
    const member_num = 7;
    const store_id = 8;
    const create_at = 9;
    const status = 10;
    const del = 11;
    const end_time = 12;
    const become_group_status = 13;
    const become_group_time = 14;
    const join_this_pintuan = 15;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::pintuan_activity_id => array(
            'name' => 'pintuan_activity_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_user_id => array(
            'name' => 'create_user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::avatar_url => array(
            'name' => 'avatar_url',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::pintuan_info => array(
            'name' => 'pintuan_info',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::member_num => array(
            'name' => 'member_num',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::create_at => array(
            'name' => 'create_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::del => array(
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::end_time => array(
            'name' => 'end_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::become_group_status => array(
            'name' => 'become_group_status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::become_group_time => array(
            'name' => 'become_group_time',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::join_this_pintuan => array(
            'name' => 'join_this_pintuan',
            'required' => false,
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
        $this->values[self::id] = null;
        $this->values[self::pintuan_activity_id] = null;
        $this->values[self::create_user_id] = null;
        $this->values[self::avatar_url] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::pintuan_info] = null;
        $this->values[self::member_num] = null;
        $this->values[self::store_id] = null;
        $this->values[self::create_at] = null;
        $this->values[self::status] = null;
        $this->values[self::del] = null;
        $this->values[self::end_time] = null;
        $this->values[self::become_group_status] = null;
        $this->values[self::become_group_time] = null;
        $this->values[self::join_this_pintuan] = null;
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

    /**
     * Sets value of 'create_user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCreateUserId($value)
    {
        return $this->set(self::create_user_id, $value);
    }

    /**
     * Returns value of 'create_user_id' property
     *
     * @return integer
     */
    public function getCreateUserId()
    {
        $value = $this->get(self::create_user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'avatar_url' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAvatarUrl($value)
    {
        return $this->set(self::avatar_url, $value);
    }

    /**
     * Returns value of 'avatar_url' property
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        $value = $this->get(self::avatar_url);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'nick_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setNickName($value)
    {
        return $this->set(self::nick_name, $value);
    }

    /**
     * Returns value of 'nick_name' property
     *
     * @return string
     */
    public function getNickName()
    {
        $value = $this->get(self::nick_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'pintuan_info' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPintuanInfo($value)
    {
        return $this->set(self::pintuan_info, $value);
    }

    /**
     * Returns value of 'pintuan_info' property
     *
     * @return integer
     */
    public function getPintuanInfo()
    {
        $value = $this->get(self::pintuan_info);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStatus($value)
    {
        return $this->set(self::status, $value);
    }

    /**
     * Returns value of 'status' property
     *
     * @return integer
     */
    public function getStatus()
    {
        $value = $this->get(self::status);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'become_group_status' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setBecomeGroupStatus($value)
    {
        return $this->set(self::become_group_status, $value);
    }

    /**
     * Returns value of 'become_group_status' property
     *
     * @return integer
     */
    public function getBecomeGroupStatus()
    {
        $value = $this->get(self::become_group_status);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'become_group_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBecomeGroupTime($value)
    {
        return $this->set(self::become_group_time, $value);
    }

    /**
     * Returns value of 'become_group_time' property
     *
     * @return string
     */
    public function getBecomeGroupTime()
    {
        $value = $this->get(self::become_group_time);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'join_this_pintuan' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setJoinThisPintuan($value)
    {
        return $this->set(self::join_this_pintuan, $value);
    }

    /**
     * Returns value of 'join_this_pintuan' property
     *
     * @return integer
     */
    public function getJoinThisPintuan()
    {
        $value = $this->get(self::join_this_pintuan);
        return $value === null ? (integer)$value : $value;
    }
}