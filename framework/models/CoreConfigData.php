<?php

namespace framework\models;

use Yii;

/**
 * This is the model class for table "core_config_data".
 *
 * @property integer $config_id
 * @property string $scope
 * @property integer $scope_id
 * @property string $path
 * @property string $value
 */
class CoreConfigData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'core_config_data';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commonDb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scope_id'], 'integer'],
            [['value'], 'string'],
            [['scope'], 'string', 'max' => 8],
            [['path'], 'string', 'max' => 255],
            [['scope', 'scope_id', 'path'], 'unique', 'targetAttribute' => ['scope', 'scope_id', 'path'], 'message' => 'The combination of Config Scope, Config Scope Id and Config Path has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_id' => 'Config Id',
            'scope' => 'Config Scope',
            'scope_id' => 'Config Scope Id',
            'path' => 'Config Path',
            'value' => 'Config Value',
        ];
    }
}
