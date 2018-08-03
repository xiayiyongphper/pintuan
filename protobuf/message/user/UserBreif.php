<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserBreif message
 */
class UserBreif extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const user_id = 1;
    const nick_name = 2;
    const gender = 3;
    const phone = 4;
    const province = 5;
    const city = 6;
    const country = 7;
    const avatar_url = 8;
    const is_robot = 9;
    const real_name = 10;
    const birthday = 11;
    const constellation = 12;
    const signature = 13;
    const has_order = 14;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::user_id => array(
            'name' => 'user_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::gender => array(
            'name' => 'gender',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::phone => array(
            'name' => 'phone',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::province => array(
            'name' => 'province',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::city => array(
            'name' => 'city',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::country => array(
            'name' => 'country',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::avatar_url => array(
            'name' => 'avatar_url',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::is_robot => array(
            'name' => 'is_robot',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::real_name => array(
            'name' => 'real_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::birthday => array(
            'name' => 'birthday',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::constellation => array(
            'name' => 'constellation',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::signature => array(
            'name' => 'signature',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::has_order => array(
            'name' => 'has_order',
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
        $this->values[self::user_id] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::gender] = null;
        $this->values[self::phone] = null;
        $this->values[self::province] = null;
        $this->values[self::city] = null;
        $this->values[self::country] = null;
        $this->values[self::avatar_url] = null;
        $this->values[self::is_robot] = null;
        $this->values[self::real_name] = null;
        $this->values[self::birthday] = null;
        $this->values[self::constellation] = null;
        $this->values[self::signature] = null;
        $this->values[self::has_order] = null;
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
     * Sets value of 'user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::user_id, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return integer
     */
    public function getUserId()
    {
        $value = $this->get(self::user_id);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'gender' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setGender($value)
    {
        return $this->set(self::gender, $value);
    }

    /**
     * Returns value of 'gender' property
     *
     * @return integer
     */
    public function getGender()
    {
        $value = $this->get(self::gender);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPhone($value)
    {
        return $this->set(self::phone, $value);
    }

    /**
     * Returns value of 'phone' property
     *
     * @return string
     */
    public function getPhone()
    {
        $value = $this->get(self::phone);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'province' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProvince($value)
    {
        return $this->set(self::province, $value);
    }

    /**
     * Returns value of 'province' property
     *
     * @return string
     */
    public function getProvince()
    {
        $value = $this->get(self::province);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'city' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCity($value)
    {
        return $this->set(self::city, $value);
    }

    /**
     * Returns value of 'city' property
     *
     * @return string
     */
    public function getCity()
    {
        $value = $this->get(self::city);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'country' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCountry($value)
    {
        return $this->set(self::country, $value);
    }

    /**
     * Returns value of 'country' property
     *
     * @return string
     */
    public function getCountry()
    {
        $value = $this->get(self::country);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'is_robot' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIsRobot($value)
    {
        return $this->set(self::is_robot, $value);
    }

    /**
     * Returns value of 'is_robot' property
     *
     * @return integer
     */
    public function getIsRobot()
    {
        $value = $this->get(self::is_robot);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'real_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRealName($value)
    {
        return $this->set(self::real_name, $value);
    }

    /**
     * Returns value of 'real_name' property
     *
     * @return string
     */
    public function getRealName()
    {
        $value = $this->get(self::real_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'birthday' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBirthday($value)
    {
        return $this->set(self::birthday, $value);
    }

    /**
     * Returns value of 'birthday' property
     *
     * @return string
     */
    public function getBirthday()
    {
        $value = $this->get(self::birthday);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'constellation' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setConstellation($value)
    {
        return $this->set(self::constellation, $value);
    }

    /**
     * Returns value of 'constellation' property
     *
     * @return string
     */
    public function getConstellation()
    {
        $value = $this->get(self::constellation);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'signature' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSignature($value)
    {
        return $this->set(self::signature, $value);
    }

    /**
     * Returns value of 'signature' property
     *
     * @return string
     */
    public function getSignature()
    {
        $value = $this->get(self::signature);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'has_order' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setHasOrder($value)
    {
        return $this->set(self::has_order, $value);
    }

    /**
     * Returns value of 'has_order' property
     *
     * @return string
     */
    public function getHasOrder()
    {
        $value = $this->get(self::has_order);
        return $value === null ? (string)$value : $value;
    }
}