<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property string $id
 * @property string $parent_id 父节点id
 * @property string $name 名称
 * @property string $code 编码
 * @property string $path 路径
 * @property int $level 层级
 * @property int $del 是否删除：1-正常，2-删除
 * @property string $create_at
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
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
            [['parent_id', 'code', 'level', 'del'], 'integer'],
            [['code', 'create_at'], 'required'],
            [['create_at'], 'safe'],
            [['name'], 'string', 'max' => 120],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'code' => 'Code',
            'path' => 'Path',
            'level' => 'Level',
            'del' => 'Del',
            'create_at' => 'Create At',
        ];
    }

    /**
     *
     * 获取省份、城市、区域列表。
     * 参数$parentId，如果传则获取该id的子列表，例如某个省的城市列表，某个城市的区域列表，
     * 不过不传参数$parentId，表示获取省份列表
     * @param $parentId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listRegion($parentId = 0)
    {
        $where = array();
        if (isset($parentId) && $parentId) {
            $where['parent_id'] = $parentId;
        } else {
            $where['level'] = 1;
        }
        $regions = Region::find()->where($where)->asArray()->all();
        return $regions;
    }

    public static function regions($code, $first, $type = 'province')
    {
        $regions = array();
        if($first){
            $regions[] = ['code' => 0, 'name' => $first];
        }
        if ($code || $type == 'province') {
            $regions = array_merge($regions, self::getRegions($code));
        }
        return $regions;
    }

    /**
     * 根据地区code获取下一级地区列表
     * @param $code
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRegions($code = 0)
    {
        //缓存key
        $cacheKey = "regions-";
        if (isset($code)) {
            $cacheKey .= $code;
        }
        $session = yii::$app->session;
        $regions = $session->get($cacheKey);
        //缓存中有
        if (null != $regions) {
            return $regions;
        }
        $where = array();
        //传code，获取code下一级地区列表
        if (isset($code) && $code) {
            $id = self::findId($code);
            $where['parent_id'] = $id;
        } else {
            $where['level'] = 1;
        }
        $regions = Region::find()->where($where)->asArray()->all();
        //存缓存
        $session->set($cacheKey, $regions);
        return $regions;
    }

    public static function getRegionByIn($in)
    {
        $where = ['in', 'code', $in];
        $regions =self::find()->where($where)->asArray()->all();
        return $regions;
    }

    public static function findId($code)
    {
        if (isset($code) && $code) {
            $region = Region::findOne(['code' => $code]);
            if (null != $region) {
                return $region['id'];
            }
        }
    }

    public static function findName($code){
        $model =self::find()->where(['code' => $code])->asArray()->one();
        if($model){
            return $model['name'];
        }
    }

    /**
     * 根据地区code获取地区id
     * @param $list
     * @param $code
     * @return mixed
     */
    public static function getId($list, $code)
    {
        foreach ($list as $item) {
            if ($code == $item['code']) {
                return $item['id'];
            }
        }
    }

    public static function getName($list, $code)
    {
        foreach ($list as $item) {
            if ($code == $item['code']) {
                return $item['name'];
            }
        }
    }

    public static function getCode($id){
        return static::findOne(['id' => $id]);
    }

    public function getCityList($pid)
    {
        $model = Region::findAll(array('id' => $pid));
        return ArrayHelper::map($model, 'id', 'name');
    }
}
