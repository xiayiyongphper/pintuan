<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * WalletRecord message
 */
class WalletRecord extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const id = 2;
    const record_number = 3;
    const amount = 4;
    const type = 5;
    const balance = 6;
    const status = 7;
    const remit_at = 8;
    const bonus_type = 9;
    const remark = 10;
    const commission_id = 11;
    const create_at = 12;
    const update_at = 13;
    const del = 14;
    const money_remark = 15;
    const after_balance = 16;
    const user_id = 17;
    const import_remark = 18;
    const import_ip = 19;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::id => array(
            'name' => 'id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::record_number => array(
            'name' => 'record_number',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::amount => array(
            'name' => 'amount',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::balance => array(
            'name' => 'balance',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::remit_at => array(
            'name' => 'remit_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::bonus_type => array(
            'name' => 'bonus_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::remark => array(
            'name' => 'remark',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::commission_id => array(
            'name' => 'commission_id',
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
            'name' => 'del',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::money_remark => array(
            'name' => 'money_remark',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::after_balance => array(
            'name' => 'after_balance',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::user_id => array(
            'name' => 'user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::import_remark => array(
            'name' => 'import_remark',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::import_ip => array(
            'name' => 'import_ip',
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
        $this->values[self::store_id] = null;
        $this->values[self::id] = null;
        $this->values[self::record_number] = null;
        $this->values[self::amount] = null;
        $this->values[self::type] = null;
        $this->values[self::balance] = null;
        $this->values[self::status] = null;
        $this->values[self::remit_at] = null;
        $this->values[self::bonus_type] = null;
        $this->values[self::remark] = null;
        $this->values[self::commission_id] = null;
        $this->values[self::create_at] = null;
        $this->values[self::update_at] = null;
        $this->values[self::del] = null;
        $this->values[self::money_remark] = null;
        $this->values[self::after_balance] = null;
        $this->values[self::user_id] = null;
        $this->values[self::import_remark] = null;
        $this->values[self::import_ip] = null;
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
     * Sets value of 'record_number' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRecordNumber($value)
    {
        return $this->set(self::record_number, $value);
    }

    /**
     * Returns value of 'record_number' property
     *
     * @return string
     */
    public function getRecordNumber()
    {
        $value = $this->get(self::record_number);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'amount' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAmount($value)
    {
        return $this->set(self::amount, $value);
    }

    /**
     * Returns value of 'amount' property
     *
     * @return string
     */
    public function getAmount()
    {
        $value = $this->get(self::amount);
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
     * Sets value of 'balance' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setBalance($value)
    {
        return $this->set(self::balance, $value);
    }

    /**
     * Returns value of 'balance' property
     *
     * @return integer
     */
    public function getBalance()
    {
        $value = $this->get(self::balance);
        return $value === null ? (integer)$value : $value;
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
     * Sets value of 'remit_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRemitAt($value)
    {
        return $this->set(self::remit_at, $value);
    }

    /**
     * Returns value of 'remit_at' property
     *
     * @return string
     */
    public function getRemitAt()
    {
        $value = $this->get(self::remit_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'bonus_type' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBonusType($value)
    {
        return $this->set(self::bonus_type, $value);
    }

    /**
     * Returns value of 'bonus_type' property
     *
     * @return string
     */
    public function getBonusType()
    {
        $value = $this->get(self::bonus_type);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'remark' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRemark($value)
    {
        return $this->set(self::remark, $value);
    }

    /**
     * Returns value of 'remark' property
     *
     * @return string
     */
    public function getRemark()
    {
        $value = $this->get(self::remark);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'commission_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCommissionId($value)
    {
        return $this->set(self::commission_id, $value);
    }

    /**
     * Returns value of 'commission_id' property
     *
     * @return integer
     */
    public function getCommissionId()
    {
        $value = $this->get(self::commission_id);
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
     * Sets value of 'money_remark' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMoneyRemark($value)
    {
        return $this->set(self::money_remark, $value);
    }

    /**
     * Returns value of 'money_remark' property
     *
     * @return string
     */
    public function getMoneyRemark()
    {
        $value = $this->get(self::money_remark);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'after_balance' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setAfterBalance($value)
    {
        return $this->set(self::after_balance, $value);
    }

    /**
     * Returns value of 'after_balance' property
     *
     * @return integer
     */
    public function getAfterBalance()
    {
        $value = $this->get(self::after_balance);
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
     * Sets value of 'import_remark' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImportRemark($value)
    {
        return $this->set(self::import_remark, $value);
    }

    /**
     * Returns value of 'import_remark' property
     *
     * @return string
     */
    public function getImportRemark()
    {
        $value = $this->get(self::import_remark);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'import_ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setImportIp($value)
    {
        return $this->set(self::import_ip, $value);
    }

    /**
     * Returns value of 'import_ip' property
     *
     * @return string
     */
    public function getImportIp()
    {
        $value = $this->get(self::import_ip);
        return $value === null ? (string)$value : $value;
    }
}