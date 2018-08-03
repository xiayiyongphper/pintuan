<?php

namespace framework\db;

/**
 * Class ActiveRecord
 * @package framework\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 只有指定对应场景，哪些操作使用事务，才会启用事务
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

}
