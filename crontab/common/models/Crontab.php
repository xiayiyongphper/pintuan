<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-8-22
 * Time: 下午7:51
 */

namespace common\models;

use framework\components\ToolsAbstract;
use framework\db\ActiveRecord;

/**
 * Class Crontab
 * @package common\models
 * @property integer $entity_id
 * @property string $name
 * @property string $route
 * @property string $cron_format
 * @property string $created_at
 * @property string $updated_at
 * @property bool $sticky
 * @property int $status
 * @property string $from_time
 * @property string $to_time
 * @property array|string $params
 * @property string $notes
 *
 */
class Crontab extends ActiveRecord
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    public $scheduledTimestamp = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crontab';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('mainDb');
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->params = $this->params ? json_decode($this->params, 1) : [];
        parent::afterFind();
    }

    /**
     * @return Crontab[]
     */
    public static function getAvailaleJobsByRoute($route)
    {
        $curDateTime = date('Y-m-d H:i:s');;
        $jobs = Crontab::find()->where([
            'status' => Crontab::STATUS_ENABLED,
            'route' => $route,
        ])->andWhere(['<=', 'from_time', $curDateTime])
            ->andWhere(['>=', 'to_time', $curDateTime])
            ->all();
        return $jobs;
    }
}