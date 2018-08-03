<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id 店铺ID
 * @property string $name 店铺名称
 * @property string $auth_token
 * @property string $open_id
 * @property string $union_id
 * @property int $wallet 钱包余额（分）
 * @property int $province 省份编码
 * @property int $city 城市编码
 * @property int $district 区/县编码
 * @property string $address 地址
 * @property string $detail_address 详细地址
 * @property int $owner_user_id 店主用户id
 * @property string $lat 超市纬度
 * @property string $lng 超市经度
 * @property string $store_phone 手机(此电话不一定和店主用户的电话一样，相互独立)
 * @property int $status 状态：0代表未审核，1代表审核通过，2代表审核不通过
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $apply_at 审核通过时间
 * @property int $type 超市类型：1-便利店，2-餐饮店，3-烟酒店，4-批零店
 * @property string $business_license_no 营业执照编号
 * @property string $business_license_img 营业执照照片
 * @property string $store_front_img 店铺正面照片
 * @property string $open_time_range 营业时间段
 * @property int $contractor_id 业务员id
 * @property int $service_id 客服id
 * @property int $delivery_type 按位存储，从低位到高位：1-自提，2-送货到家。如：只有自提-1，可以自提和送货到家-3
 * @property string $bank 开户行
 * @property string $account 银行账号
 * @property string $account_name 开户名称
 * @property string $commission_coefficient 佣金系数
 * @property string $mini_program_qrcode 小程序二维码
 * @property string $receive_goods_qrcode 客户提货二维码
 * @property string $wx_qrcode 微信二维码
 * @property string $owner_user_photo 店主照片
 * @property string $owner_user_name 店主名字
 * @property string $bank_card_photo 银行卡照片
 * @property int $del 是否删除：1-正常，2-删除
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wholesalerDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet', 'province', 'city', 'district',  'owner_user_id', 'status', 'type', 'contractor_id', 'service_id', 'delivery_type', 'del','commission_id','group_num'], 'integer'],
            [['created_at', 'updated_at', 'apply_at'], 'safe'],
            [['commission_coefficient'], 'number'],
            [['name','address', 'detail_address', 'business_license_no', 'business_license_img',
                'store_front_img', 'open_time_range', 'mini_program_qrcode', 'receive_goods_qrcode', 'wx_qrcode',
                'owner_user_photo', 'bank_card_photo', 'owner_user_name','group_nickname'], 'string', 'max' => 255],
            [['account', 'account_name'], 'string', 'max' => 100],
            [['lat', 'lng'], 'string', 'max' => 32],
            [['store_phone'], 'string', 'max' => 24],
            [['bank'], 'string', 'max' => 200],
            [['store_phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
           // 'auth_token' => 'Auth Token',
            //'open_id' => 'Open ID',
            //'union_id' => 'Union ID',
            'wallet' => 'Wallet',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'address' => 'Address',
            'detail_address' => 'Detail Address',
            'owner_user_id' => 'Owner User ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'store_phone' => 'Store Phone',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'apply_at' => 'Apply At',
            'type' => 'Type',
            'business_license_no' => 'Business License No',
            'business_license_img' => 'Business License Img',
            'store_front_img' => 'Store Front Img',
            'open_time_range' => 'Open Time Range',
            'contractor_id' => 'Contractor ID',
            'service_id' => 'Service ID',
            'delivery_type' => 'Delivery Type',
            'bank' => '开户行',
            'account' => '银行卡号',
            'account_name' => '开户名称',
            'commission_coefficient' => '佣金系数',
            'mini_program_qrcode' => '小程序二维码',
            'receive_goods_qrcode' => '收货二维码',
            'wx_qrcode' => '微信二维码',
            'owner_user_name' => '店主名字',
            'owner_user_photo' => '店主图片',
            'del' => 'Del',
            'commission_id'=>'店铺佣金类型',
            'group_nickname'=>'群昵称',
            'group_num'=>'群人数',
        ];
    }

    public static function findById($id){
        return static::findOne(['id' => $id]);
    }

    public static function getInfo($id){
        return self::find()->where(['id' => $id])->asArray()->one();
    }

    public function getList($id)
    {
        return self::find()->where(['id' => $id])->asArray()->all();
    }
    // 店铺状态
    public static function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return "未审核";
            case 1:
                return "正常营业";
            case 2:
                return "暂停营业";
            default:
                return "未审核";
        }
    }
}
