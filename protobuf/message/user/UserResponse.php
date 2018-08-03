<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserResponse message
 */
class UserResponse extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const auth_token = 1;
    const is_auth = 2;
    const user_id = 3;
    const nick_name = 4;
    const gender = 5;
    const phone = 6;
    const province = 7;
    const city = 8;
    const country = 9;
    const avatar_url = 10;
    const is_robot = 11;
    const store_id = 12;
    const user_store = 13;
    const user_store_id = 14;
    const real_name = 15;
    const birthday = 16;
    const constellation = 17;
    const signature = 18;
    const open_id = 19;
    const own_store_id = 20;
    const has_order = 21;
    const store_count = 22;
    const position = 23;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::auth_token => array(
            'name' => 'auth_token',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::is_auth => array(
            'name' => 'is_auth',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => false,
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
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::is_robot => array(
            'name' => 'is_robot',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_store => array(
            'name' => 'user_store',
            'repeated' => true,
            'type' => '\message\user\UserStore'
        ),
        self::user_store_id => array(
            'name' => 'user_store_id',
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
        self::open_id => array(
            'name' => 'open_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::own_store_id => array(
            'name' => 'own_store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::has_order => array(
            'name' => 'has_order',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_count => array(
            'name' => 'store_count',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::position => array(
            'name' => 'position',
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
        $this->values[self::auth_token] = null;
        $this->values[self::is_auth] = null;
        $this->values[self::user_id] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::gender] = null;
        $this->values[self::phone] = null;
        $this->values[self::province] = null;
        $this->values[self::city] = null;
        $this->values[self::country] = null;
        $this->values[self::avatar_url] = null;
        $this->values[self::is_robot] = null;
        $this->values[self::store_id] = null;
        $this->values[self::user_store] = array();
        $this->values[self::user_store_id] = null;
        $this->values[self::real_name] = null;
        $this->values[self::birthday] = null;
        $this->values[self::constellation] = null;
        $this->values[self::signature] = null;
        $this->values[self::open_id] = null;
        $this->values[self::own_store_id] = null;
        $this->values[self::has_order] = null;
        $this->values[self::store_count] = null;
        $this->values[self::position] = null;
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
     * Sets value of 'auth_token' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAuthToken($value)
    {
        return $this->set(self::auth_token, $value);
    }

    /**
     * Returns value of 'auth_token' property
     *
     * @return string
     */
    public function getAuthToken()
    {
        $value = $this->get(self::auth_token);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'is_auth' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIsAuth($value)
    {
        return $this->set(self::is_auth, $value);
    }

    /**
     * Returns value of 'is_auth' property
     *
     * @return integer
     */
    public function getIsAuth()
    {
        $value = $this->get(self::is_auth);
        return $value === null ? (integer)$value : $value;
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
     * Appends value to 'user_store' list
     *
     * @param \message\user\UserStore $value Value to append
     *
     * @return null
     */
    public function appendUserStore(\message\user\UserStore $value)
    {
        return $this->append(self::user_store, $value);
    }

    /**
     * Clears 'user_store' list
     *
     * @return null
     */
    public function clearUserStore()
    {
        return $this->clear(self::user_store);
    }

    /**
     * Returns 'user_store' list
     *
     * @return \message\user\UserStore[]
     */
    public function getUserStore()
    {
        return $this->get(self::user_store);
    }

    /**
     * Returns 'user_store' iterator
     *
     * @return \ArrayIterator
     */
    public function getUserStoreIterator()
    {
        return new \ArrayIterator($this->get(self::user_store));
    }

    /**
     * Returns element from 'user_store' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\user\UserStore
     */
    public function getUserStoreAt($offset)
    {
        return $this->get(self::user_store, $offset);
    }

    /**
     * Returns count of 'user_store' list
     *
     * @return int
     */
    public function getUserStoreCount()
    {
        return $this->count(self::user_store);
    }

    /**
     * Sets value of 'user_store_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setUserStoreId($value)
    {
        return $this->set(self::user_store_id, $value);
    }

    /**
     * Returns value of 'user_store_id' property
     *
     * @return integer
     */
    public function getUserStoreId()
    {
        $value = $this->get(self::user_store_id);
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
     * Sets value of 'open_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOpenId($value)
    {
        return $this->set(self::open_id, $value);
    }

    /**
     * Returns value of 'open_id' property
     *
     * @return string
     */
    public function getOpenId()
    {
        $value = $this->get(self::open_id);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'own_store_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOwnStoreId($value)
    {
        return $this->set(self::own_store_id, $value);
    }

    /**
     * Returns value of 'own_store_id' property
     *
     * @return string
     */
    public function getOwnStoreId()
    {
        $value = $this->get(self::own_store_id);
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

    /**
     * Sets value of 'store_count' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setStoreCount($value)
    {
        return $this->set(self::store_count, $value);
    }

    /**
     * Returns value of 'store_count' property
     *
     * @return integer
     */
    public function getStoreCount()
    {
        $value = $this->get(self::store_count);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'position' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setPosition($value)
    {
        return $this->set(self::position, $value);
    }

    /**
     * Returns value of 'position' property
     *
     * @return integer
     */
    public function getPosition()
    {
        $value = $this->get(self::position);
        return $value === null ? (integer)$value : $value;
    }
}