<?php
/**
 *
 * message.store package
 */

namespace message\store;
/**
 * Store message
 */
class Store extends \framework\protocolbuffers\Message
{
    /* Field index constants */
    const store_id = 1;
    const store_name = 2;
    const wallet = 3;
    const province = 4;
    const city = 5;
    const district = 6;
    const address = 7;
    const detail_address = 8;
    const owner_user_id = 9;
    const lat = 10;
    const lng = 11;
    const store_phone = 12;
    const status = 13;
    const created_at = 14;
    const updated_at = 15;
    const apply_at = 16;
    const type = 17;
    const business_license_no = 18;
    const business_license_img = 19;
    const store_front_img = 20;
    const open_time_range = 21;
    const contractor_id = 22;
    const service_id = 23;
    const delivery_type = 24;
    const bank = 25;
    const account = 26;
    const account_name = 27;
    const commission_coefficient = 28;
    const mini_program_qrcode = 29;
    const receive_goods_qrcode = 30;
    const wx_qrcode = 31;
    const owner_user_name = 32;
    const owner_user_photo = 33;
    const bank_card_photo = 34;
    const nick_name = 35;
    const avatar_url = 36;
    const distance = 37;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::store_id => array(
            'name' => 'store_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::store_name => array(
            'name' => 'store_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::wallet => array(
            'name' => 'wallet',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::province => array(
            'name' => 'province',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::city => array(
            'name' => 'city',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::district => array(
            'name' => 'district',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::address => array(
            'name' => 'address',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::detail_address => array(
            'name' => 'detail_address',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::owner_user_id => array(
            'name' => 'owner_user_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::lat => array(
            'name' => 'lat',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::lng => array(
            'name' => 'lng',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_phone => array(
            'name' => 'store_phone',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::status => array(
            'name' => 'status',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::created_at => array(
            'name' => 'created_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::updated_at => array(
            'name' => 'updated_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::apply_at => array(
            'name' => 'apply_at',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::type => array(
            'name' => 'type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::business_license_no => array(
            'name' => 'business_license_no',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::business_license_img => array(
            'name' => 'business_license_img',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::store_front_img => array(
            'name' => 'store_front_img',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::open_time_range => array(
            'name' => 'open_time_range',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::contractor_id => array(
            'name' => 'contractor_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::service_id => array(
            'name' => 'service_id',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::delivery_type => array(
            'name' => 'delivery_type',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::bank => array(
            'name' => 'bank',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::account => array(
            'name' => 'account',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::account_name => array(
            'name' => 'account_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::commission_coefficient => array(
            'name' => 'commission_coefficient',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::mini_program_qrcode => array(
            'name' => 'mini_program_qrcode',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::receive_goods_qrcode => array(
            'name' => 'receive_goods_qrcode',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::wx_qrcode => array(
            'name' => 'wx_qrcode',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::owner_user_name => array(
            'name' => 'owner_user_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::owner_user_photo => array(
            'name' => 'owner_user_photo',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::bank_card_photo => array(
            'name' => 'bank_card_photo',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::nick_name => array(
            'name' => 'nick_name',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::avatar_url => array(
            'name' => 'avatar_url',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::distance => array(
            'name' => 'distance',
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
        $this->values[self::store_name] = null;
        $this->values[self::wallet] = null;
        $this->values[self::province] = null;
        $this->values[self::city] = null;
        $this->values[self::district] = null;
        $this->values[self::address] = null;
        $this->values[self::detail_address] = null;
        $this->values[self::owner_user_id] = null;
        $this->values[self::lat] = null;
        $this->values[self::lng] = null;
        $this->values[self::store_phone] = null;
        $this->values[self::status] = null;
        $this->values[self::created_at] = null;
        $this->values[self::updated_at] = null;
        $this->values[self::apply_at] = null;
        $this->values[self::type] = null;
        $this->values[self::business_license_no] = null;
        $this->values[self::business_license_img] = null;
        $this->values[self::store_front_img] = null;
        $this->values[self::open_time_range] = null;
        $this->values[self::contractor_id] = null;
        $this->values[self::service_id] = null;
        $this->values[self::delivery_type] = null;
        $this->values[self::bank] = null;
        $this->values[self::account] = null;
        $this->values[self::account_name] = null;
        $this->values[self::commission_coefficient] = null;
        $this->values[self::mini_program_qrcode] = null;
        $this->values[self::receive_goods_qrcode] = null;
        $this->values[self::wx_qrcode] = null;
        $this->values[self::owner_user_name] = null;
        $this->values[self::owner_user_photo] = null;
        $this->values[self::bank_card_photo] = null;
        $this->values[self::nick_name] = null;
        $this->values[self::avatar_url] = null;
        $this->values[self::distance] = null;
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
     * Sets value of 'store_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStoreName($value)
    {
        return $this->set(self::store_name, $value);
    }

    /**
     * Returns value of 'store_name' property
     *
     * @return string
     */
    public function getStoreName()
    {
        $value = $this->get(self::store_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'wallet' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setWallet($value)
    {
        return $this->set(self::wallet, $value);
    }

    /**
     * Returns value of 'wallet' property
     *
     * @return integer
     */
    public function getWallet()
    {
        $value = $this->get(self::wallet);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'province' property
     *
     * @param integer $value Property value
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
     * @return integer
     */
    public function getProvince()
    {
        $value = $this->get(self::province);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'city' property
     *
     * @param integer $value Property value
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
     * @return integer
     */
    public function getCity()
    {
        $value = $this->get(self::city);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'district' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDistrict($value)
    {
        return $this->set(self::district, $value);
    }

    /**
     * Returns value of 'district' property
     *
     * @return integer
     */
    public function getDistrict()
    {
        $value = $this->get(self::district);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'address' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddress($value)
    {
        return $this->set(self::address, $value);
    }

    /**
     * Returns value of 'address' property
     *
     * @return string
     */
    public function getAddress()
    {
        $value = $this->get(self::address);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'detail_address' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDetailAddress($value)
    {
        return $this->set(self::detail_address, $value);
    }

    /**
     * Returns value of 'detail_address' property
     *
     * @return string
     */
    public function getDetailAddress()
    {
        $value = $this->get(self::detail_address);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'owner_user_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setOwnerUserId($value)
    {
        return $this->set(self::owner_user_id, $value);
    }

    /**
     * Returns value of 'owner_user_id' property
     *
     * @return integer
     */
    public function getOwnerUserId()
    {
        $value = $this->get(self::owner_user_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'lat' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setLat($value)
    {
        return $this->set(self::lat, $value);
    }

    /**
     * Returns value of 'lat' property
     *
     * @return string
     */
    public function getLat()
    {
        $value = $this->get(self::lat);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'lng' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setLng($value)
    {
        return $this->set(self::lng, $value);
    }

    /**
     * Returns value of 'lng' property
     *
     * @return string
     */
    public function getLng()
    {
        $value = $this->get(self::lng);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'store_phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStorePhone($value)
    {
        return $this->set(self::store_phone, $value);
    }

    /**
     * Returns value of 'store_phone' property
     *
     * @return string
     */
    public function getStorePhone()
    {
        $value = $this->get(self::store_phone);
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
     * Sets value of 'created_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCreatedAt($value)
    {
        return $this->set(self::created_at, $value);
    }

    /**
     * Returns value of 'created_at' property
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $value = $this->get(self::created_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'updated_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUpdatedAt($value)
    {
        return $this->set(self::updated_at, $value);
    }

    /**
     * Returns value of 'updated_at' property
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        $value = $this->get(self::updated_at);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'apply_at' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setApplyAt($value)
    {
        return $this->set(self::apply_at, $value);
    }

    /**
     * Returns value of 'apply_at' property
     *
     * @return string
     */
    public function getApplyAt()
    {
        $value = $this->get(self::apply_at);
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
     * Sets value of 'business_license_no' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBusinessLicenseNo($value)
    {
        return $this->set(self::business_license_no, $value);
    }

    /**
     * Returns value of 'business_license_no' property
     *
     * @return string
     */
    public function getBusinessLicenseNo()
    {
        $value = $this->get(self::business_license_no);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'business_license_img' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBusinessLicenseImg($value)
    {
        return $this->set(self::business_license_img, $value);
    }

    /**
     * Returns value of 'business_license_img' property
     *
     * @return string
     */
    public function getBusinessLicenseImg()
    {
        $value = $this->get(self::business_license_img);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'store_front_img' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStoreFrontImg($value)
    {
        return $this->set(self::store_front_img, $value);
    }

    /**
     * Returns value of 'store_front_img' property
     *
     * @return string
     */
    public function getStoreFrontImg()
    {
        $value = $this->get(self::store_front_img);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'open_time_range' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOpenTimeRange($value)
    {
        return $this->set(self::open_time_range, $value);
    }

    /**
     * Returns value of 'open_time_range' property
     *
     * @return string
     */
    public function getOpenTimeRange()
    {
        $value = $this->get(self::open_time_range);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'contractor_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setContractorId($value)
    {
        return $this->set(self::contractor_id, $value);
    }

    /**
     * Returns value of 'contractor_id' property
     *
     * @return integer
     */
    public function getContractorId()
    {
        $value = $this->get(self::contractor_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'service_id' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setServiceId($value)
    {
        return $this->set(self::service_id, $value);
    }

    /**
     * Returns value of 'service_id' property
     *
     * @return integer
     */
    public function getServiceId()
    {
        $value = $this->get(self::service_id);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'delivery_type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setDeliveryType($value)
    {
        return $this->set(self::delivery_type, $value);
    }

    /**
     * Returns value of 'delivery_type' property
     *
     * @return integer
     */
    public function getDeliveryType()
    {
        $value = $this->get(self::delivery_type);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'bank' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBank($value)
    {
        return $this->set(self::bank, $value);
    }

    /**
     * Returns value of 'bank' property
     *
     * @return string
     */
    public function getBank()
    {
        $value = $this->get(self::bank);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'account' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAccount($value)
    {
        return $this->set(self::account, $value);
    }

    /**
     * Returns value of 'account' property
     *
     * @return string
     */
    public function getAccount()
    {
        $value = $this->get(self::account);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'account_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAccountName($value)
    {
        return $this->set(self::account_name, $value);
    }

    /**
     * Returns value of 'account_name' property
     *
     * @return string
     */
    public function getAccountName()
    {
        $value = $this->get(self::account_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'commission_coefficient' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCommissionCoefficient($value)
    {
        return $this->set(self::commission_coefficient, $value);
    }

    /**
     * Returns value of 'commission_coefficient' property
     *
     * @return string
     */
    public function getCommissionCoefficient()
    {
        $value = $this->get(self::commission_coefficient);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'mini_program_qrcode' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMiniProgramQrcode($value)
    {
        return $this->set(self::mini_program_qrcode, $value);
    }

    /**
     * Returns value of 'mini_program_qrcode' property
     *
     * @return string
     */
    public function getMiniProgramQrcode()
    {
        $value = $this->get(self::mini_program_qrcode);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'receive_goods_qrcode' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setReceiveGoodsQrcode($value)
    {
        return $this->set(self::receive_goods_qrcode, $value);
    }

    /**
     * Returns value of 'receive_goods_qrcode' property
     *
     * @return string
     */
    public function getReceiveGoodsQrcode()
    {
        $value = $this->get(self::receive_goods_qrcode);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'wx_qrcode' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setWxQrcode($value)
    {
        return $this->set(self::wx_qrcode, $value);
    }

    /**
     * Returns value of 'wx_qrcode' property
     *
     * @return string
     */
    public function getWxQrcode()
    {
        $value = $this->get(self::wx_qrcode);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'owner_user_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOwnerUserName($value)
    {
        return $this->set(self::owner_user_name, $value);
    }

    /**
     * Returns value of 'owner_user_name' property
     *
     * @return string
     */
    public function getOwnerUserName()
    {
        $value = $this->get(self::owner_user_name);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'owner_user_photo' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOwnerUserPhoto($value)
    {
        return $this->set(self::owner_user_photo, $value);
    }

    /**
     * Returns value of 'owner_user_photo' property
     *
     * @return string
     */
    public function getOwnerUserPhoto()
    {
        $value = $this->get(self::owner_user_photo);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'bank_card_photo' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBankCardPhoto($value)
    {
        return $this->set(self::bank_card_photo, $value);
    }

    /**
     * Returns value of 'bank_card_photo' property
     *
     * @return string
     */
    public function getBankCardPhoto()
    {
        $value = $this->get(self::bank_card_photo);
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
     * Sets value of 'distance' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDistance($value)
    {
        return $this->set(self::distance, $value);
    }

    /**
     * Returns value of 'distance' property
     *
     * @return string
     */
    public function getDistance()
    {
        $value = $this->get(self::distance);
        return $value === null ? (string)$value : $value;
    }
}