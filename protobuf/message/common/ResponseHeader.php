<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * ResponseHeader message
 */
class ResponseHeader extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const code = 1;
    const msg = 2;
    const timestamp = 3;
    const route = 4;
    const request_id = 5;
    const content_type = 6;
    const checksum = 7;
    const filename = 8;
    const encrypt = 9;
    const encrypt_version = 10;
    const protocol = 11;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::code => array(
            'default' => 0,
            'name' => 'code',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::msg => array(
            'name' => 'msg',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::timestamp => array(
            'name' => 'timestamp',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::route => array(
            'name' => 'route',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::request_id => array(
            'name' => 'request_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::content_type => array(
            'default' => \message\common\ContentType::APPLICATION_PB_STREAM,
            'name' => 'content_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::checksum => array(
            'name' => 'checksum',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::filename => array(
            'name' => 'filename',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::encrypt => array(
            'default' => \message\common\EncryptionMethod::DES,
            'name' => 'encrypt',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::encrypt_version => array(
            'default' => 1,
            'name' => 'encrypt_version',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::protocol => array(
            'default' => \message\common\Protocol::PB,
            'name' => 'protocol',
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
        $this->values[self::code] = self::$fields[self::code]['default'];
        $this->values[self::msg] = null;
        $this->values[self::timestamp] = null;
        $this->values[self::route] = null;
        $this->values[self::request_id] = null;
        $this->values[self::content_type] = self::$fields[self::content_type]['default'];
        $this->values[self::checksum] = null;
        $this->values[self::filename] = null;
        $this->values[self::encrypt] = self::$fields[self::encrypt]['default'];
        $this->values[self::encrypt_version] = self::$fields[self::encrypt_version]['default'];
        $this->values[self::protocol] = self::$fields[self::protocol]['default'];
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
     * @param integer $value Property value
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
     * @return integer
     */
    public function getCode()
    {
        $value = $this->get(self::code);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'msg' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMsg($value)
    {
        return $this->set(self::msg, $value);
    }

    /**
     * Returns value of 'msg' property
     *
     * @return string
     */
    public function getMsg()
    {
        $value = $this->get(self::msg);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'timestamp' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTimestamp($value)
    {
        return $this->set(self::timestamp, $value);
    }

    /**
     * Returns value of 'timestamp' property
     *
     * @return string
     */
    public function getTimestamp()
    {
        $value = $this->get(self::timestamp);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'route' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRoute($value)
    {
        return $this->set(self::route, $value);
    }

    /**
     * Returns value of 'route' property
     *
     * @return string
     */
    public function getRoute()
    {
        $value = $this->get(self::route);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'request_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setRequestId($value)
    {
        return $this->set(self::request_id, $value);
    }

    /**
     * Returns value of 'request_id' property
     *
     * @return integer
     */
    public function getRequestId()
    {
        $value = $this->get(self::request_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'content_type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setContentType($value)
    {
        return $this->set(self::content_type, $value);
    }

    /**
     * Returns value of 'content_type' property
     *
     * @return integer
     */
    public function getContentType()
    {
        $value = $this->get(self::content_type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'checksum' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setChecksum($value)
    {
        return $this->set(self::checksum, $value);
    }

    /**
     * Returns value of 'checksum' property
     *
     * @return string
     */
    public function getChecksum()
    {
        $value = $this->get(self::checksum);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'filename' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setFilename($value)
    {
        return $this->set(self::filename, $value);
    }

    /**
     * Returns value of 'filename' property
     *
     * @return string
     */
    public function getFilename()
    {
        $value = $this->get(self::filename);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'encrypt' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setEncrypt($value)
    {
        return $this->set(self::encrypt, $value);
    }

    /**
     * Returns value of 'encrypt' property
     *
     * @return integer
     */
    public function getEncrypt()
    {
        $value = $this->get(self::encrypt);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'encrypt_version' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setEncryptVersion($value)
    {
        return $this->set(self::encrypt_version, $value);
    }

    /**
     * Returns value of 'encrypt_version' property
     *
     * @return integer
     */
    public function getEncryptVersion()
    {
        $value = $this->get(self::encrypt_version);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'protocol' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setProtocol($value)
    {
        return $this->set(self::protocol, $value);
    }

    /**
     * Returns value of 'protocol' property
     *
     * @return integer
     */
    public function getProtocol()
    {
        $value = $this->get(self::protocol);
        return $value === null ? (integer)$value : $value;
    }
}