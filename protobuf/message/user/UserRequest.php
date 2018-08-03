<?php
/**
 *
 * message.user package
 */

namespace message\user;
/**
 * UserRequest message
 */
class UserRequest extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const code = 1;
    const user_id = 2;
    const auth_token = 3;
    const raw_data = 4;
    const signature = 5;
    const encrypted_data = 6;
    const iv = 7;
    const province = 8;
    const city = 9;
    const country = 10;
    const birthday = 11;
    const constellation = 12;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::code => array(
            'name' => 'code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::auth_token => array(
            'name' => 'auth_token',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::raw_data => array(
            'name' => 'raw_data',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::signature => array(
            'name' => 'signature',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::encrypted_data => array(
            'name' => 'encrypted_data',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::iv => array(
            'name' => 'iv',
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
        $this->values[self::code] = null;
        $this->values[self::user_id] = null;
        $this->values[self::auth_token] = null;
        $this->values[self::raw_data] = null;
        $this->values[self::signature] = null;
        $this->values[self::encrypted_data] = null;
        $this->values[self::iv] = null;
        $this->values[self::province] = null;
        $this->values[self::city] = null;
        $this->values[self::country] = null;
        $this->values[self::birthday] = null;
        $this->values[self::constellation] = null;
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
     * Sets value of 'code' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCode($value)
    {
        return $this->set(self::code, $value);
    }

    /**
     * Returns value of 'code' property
     *
     * @return string
     */
    public function getCode()
    {
        $value = $this->get(self::code);
        return $value === null ? (string)$value : $value;
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
     * Sets value of 'raw_data' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRawData($value)
    {
        return $this->set(self::raw_data, $value);
    }

    /**
     * Returns value of 'raw_data' property
     *
     * @return string
     */
    public function getRawData()
    {
        $value = $this->get(self::raw_data);
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
     * Sets value of 'encrypted_data' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEncryptedData($value)
    {
        return $this->set(self::encrypted_data, $value);
    }

    /**
     * Returns value of 'encrypted_data' property
     *
     * @return string
     */
    public function getEncryptedData()
    {
        $value = $this->get(self::encrypted_data);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'iv' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setIv($value)
    {
        return $this->set(self::iv, $value);
    }

    /**
     * Returns value of 'iv' property
     *
     * @return string
     */
    public function getIv()
    {
        $value = $this->get(self::iv);
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
}