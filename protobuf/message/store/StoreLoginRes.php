<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * StoreLoginRes message
 */
class StoreLoginRes extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const auth_token = 1;
    const is_auth = 2;
    const store_id = 3;
    const nick_name = 4;
    const gender = 5;
    const country = 6;
    const avatar_url = 7;
    const store = 8;
    const real_name = 9;
    const is_merchant = 10;

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
        self::store_id => array(
            'name' => 'store_id',
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
        self::store => array(
            'name' => 'store',
            'repeated' => true,
            'type' => '\message\store\Store'
        ),
        self::real_name => array(
            'name' => 'real_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::is_merchant => array(
            'name' => 'is_merchant',
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
        $this->values[self::store_id] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::gender] = null;
        $this->values[self::country] = null;
        $this->values[self::avatar_url] = null;
        $this->values[self::store] = array();
        $this->values[self::real_name] = null;
        $this->values[self::is_merchant] = null;
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
     * Appends value to 'store' list
     *
     * @param \message\store\Store $value Value to append
     *
     * @return null
     */
    public function appendStore(\message\store\Store $value)
    {
        return $this->append(self::store, $value);
    }

    /**
     * Clears 'store' list
     *
     * @return null
     */
    public function clearStore()
    {
        return $this->clear(self::store);
    }

    /**
     * Returns 'store' list
     *
     * @return \message\store\Store[]
     */
    public function getStore()
    {
        return $this->get(self::store);
    }

    /**
     * Returns 'store' iterator
     *
     * @return \ArrayIterator
     */
    public function getStoreIterator()
    {
        return new \ArrayIterator($this->get(self::store));
    }

    /**
     * Returns element from 'store' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \message\store\Store
     */
    public function getStoreAt($offset)
    {
        return $this->get(self::store, $offset);
    }

    /**
     * Returns count of 'store' list
     *
     * @return int
     */
    public function getStoreCount()
    {
        return $this->count(self::store);
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
     * Sets value of 'is_merchant' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIsMerchant($value)
    {
        return $this->set(self::is_merchant, $value);
    }

    /**
     * Returns value of 'is_merchant' property
     *
     * @return integer
     */
    public function getIsMerchant()
    {
        $value = $this->get(self::is_merchant);
        return $value === null ? (integer)$value : $value;
    }
}