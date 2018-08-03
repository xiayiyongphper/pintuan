<?php

namespace common\models\pintuan;

use framework\components\ToolsAbstract;
use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "pintuan_activity".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $cover_picture 拼团活动封面图
 * @property int $product_id 商品id spu的id
 * @property int $wholesaler_id 供应商id
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 * @property int $type 拼团类型：1-单点拼团，2-多点拼团
 * @property string $strategy 人数策略，json格式，如："{"base_member_num":{"after_start_min":1,"member_num":9},"auto_increment":{"before_end_min":60,"increment_cycle_min":10},"fill_before_end":{"before_end_min":5}}"
 * @property int $member_num 参团人数
 * @property int $continue_pintuan 超过可继续拼团 1是 2否
 * @property string $sort    排序权重
 * @property string $create_at
 * @property string $update_at
 * @property int $status 拼团活动是否手动结束：1未结束，2已结束
 * @property int $del 是否删除：1-正常，2-删除
 */
class PintuanActivity extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pintuan_activity';
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
    }


}
