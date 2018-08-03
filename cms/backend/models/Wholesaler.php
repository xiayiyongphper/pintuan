<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wholesaler".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $service_phone 客服电话
 * @property string $phone 联系电话
 * @property string $province 省份编码
 * @property string $business_license_code 营业执照注册号
 * @property string $city 城市编码
 * @property string $district 区/县编码
 * @property string $store_address 店铺地址
 * @property string $business_license_img 营业执照照片
 * @property string $license 经营许可证
 * @property string $tax_registration_certificate_img 税务登记证图片
 * @property string $organization_code_certificate_img 组织机构代码证图片
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $status 状态0：未审核，1:正常营业,2:暂停营业，3：封号，4：审核不通过
 * @property int $sort 供应商权重，1-1000，供货商权重影响首页的供货商列表、全部供货商列表页、商品列表页和搜索结果页
 * @property string $short_name 供应商简称
 * @property string $remark 备注
 * @property string $settlement_cycle 结算周期(天）
 * @property string $bank 开户行
 * @property string $account 银行账号
 * @property string $account_name 开户名称
 * @property string $invoice_title 发票抬头
 * @property string $margin 保证金（分）
 * @property int $del 是否删除：1-正常，2-删除
 */
class Wholesaler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        preg_match("/dbname=([^;]+)/i", self::getDb()->dsn, $matches);
        return $matches[1] . '.wholesaler';
//        return 'wholesaler';
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
            [['province', 'city', 'district', 'status', 'sort', 'settlement_cycle', 'margin', 'del'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'phone', 'store_address', 'business_license_img', 'license', 'tax_registration_certificate_img', 'organization_code_certificate_img', 'invoice_title'], 'string', 'max' => 255],
            [['service_phone', 'business_license_code'], 'string', 'max' => 50],
            [['lng', 'lat'], 'string', 'max' => 32],
            [['short_name', 'account', 'account_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 126],
            [['bank'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'service_phone' => Yii::t('app', 'Service Phone'),
            'phone' => Yii::t('app', 'Phone'),
            'province' => Yii::t('app', 'Province'),
            'business_license_code' => Yii::t('app', 'Business License Code'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'store_address' => Yii::t('app', 'Store Address'),
            'business_license_img' => Yii::t('app', 'Business License Img'),
            'license' => Yii::t('app', 'License'),
            'tax_registration_certificate_img' => Yii::t('app', 'Tax Registration Certificate Img'),
            'organization_code_certificate_img' => Yii::t('app', 'Organization Code Certificate Img'),
            'lng' => Yii::t('app', 'Lng'),
            'lat' => Yii::t('app', 'Lat'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'sort' => Yii::t('app', 'Sort'),
            'short_name' => Yii::t('app', 'Short Name'),
            'remark' => Yii::t('app', 'Remark'),
            'settlement_cycle' => Yii::t('app', 'Settlement Cycle'),
            'bank' => Yii::t('app', 'Bank'),
            'account' => Yii::t('app', 'Account'),
            'account_name' => Yii::t('app', 'Account Name'),
            'invoice_title' => Yii::t('app', 'Invoice Title'),
            'margin' => Yii::t('app', 'Margin'),
            'del' => Yii::t('app', 'Del'),
        ];
    }

    public static function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return "未审核";
            case 1:
                return "正常营业";
            case 2:
                return "暂停营业";
            case 3:
                return "封号";
            case 4:
                return "审核不通过";
            default:
                return "未审核";
        }
    }

    /**
     * 添加数据
     * @param unknown $data
     * @return number|string
     */
    public function add($data)
    {
        $res =  Yii::$app->wholesalerDb->createCommand()->insert('wholesaler', $data)->execute();

        if (!$res) {
            return $res;
        }

        return Yii::$app->wholesalerDb->getLastInsertID();
    }
}
