<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * StoreLoginReq message
 */
class StoreLoginReq extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const code = 1;
    const store_id = 2;
    const auth_token = 3;
    const raw_data = 4;
    const signature = 5;
    const encrypted_data = 6;
    const iv = 7;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::code => array(
            'name' => 'code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_id => array(
            'name' => 'store_id',
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
        $this->values[self::store_id] = null;
        $this->values[self::auth_token] = null;
        $this->values[self::raw_data] = null;
        $this->values[self::signature] = null;
        $this->values[self::encrypted_data] = null;
        $this->values[self::iv] = null;
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
}