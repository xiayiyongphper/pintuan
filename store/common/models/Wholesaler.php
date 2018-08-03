<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wholesaler".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $service_phone 客服电话
 * @property string $phone 联系电话
 * @property int $province 省份编码
 * @property string $business_license_code 营业执照注册号
 * @property int $city 城市编码
 * @property int $district 区/县编码
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
 * @property int $settlement_cycle 结算周期(天）
 * @property string $bank 开户行
 * @property string $account 银行账号
 * @property string $account_name 开户名称
 * @property string $invoice_title 发票抬头
 * @property int $margin 保证金（分）
 * @property int $del 是否删除：1-正常，2-删除
 */
class Wholesaler extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的
    const REVIEW_PASSED = 1;//审核通过

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wholesaler';
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
            'id' => 'ID',
            'name' => '名称',
            'service_phone' => '客服电话',
            'phone' => '联系电话',
            'province' => '省份编码',
            'business_license_code' => '营业执照注册号',
            'city' => '城市编码',
            'district' => '区/县编码',
            'store_address' => '店铺地址',
            'business_license_img' => '营业执照照片',
            'license' => '经营许可证',
            'tax_registration_certificate_img' => '税务登记证图片',
            'organization_code_certificate_img' => '组织机构代码证图片',
            'lng' => '经度',
            'lat' => '纬度',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态0：未审核，1:正常营业,2:暂停营业，3：封号，4：审核不通过',
            'sort' => '供应商权重，1-1000，供货商权重影响首页的供货商列表、全部供货商列表页、商品列表页和搜索结果页',
            'short_name' => '供应商简称',
            'remark' => '备注',
            'settlement_cycle' => '结算周期(天）',
            'bank' => '开户行',
            'account' => '银行账号',
            'account_name' => '开户名称',
            'invoice_title' => '发票抬头',
            'margin' => '保证金（分）',
            'del' => '是否删除：1-正常，2-删除',
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
}
