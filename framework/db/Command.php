<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace framework\db;

class Command extends \yii\db\Command
{
    /**
     * @var Connection
     */
    public $db;

    public function prepare($forRead = null)
    {
        $this->db->check();//add by henry zxj prevent client working
        parent::prepare($forRead);
    }
}
