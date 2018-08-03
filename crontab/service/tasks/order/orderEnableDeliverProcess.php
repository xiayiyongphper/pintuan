<?php

/**
 * Created by PhpStorm.
 * User: wjq310
 * Date: 2018/07/17
 * Time: 17:43
 */

namespace service\tasks\order;

use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\pintuan\Pintuan;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;


class orderEnableDeliverProcess extends TaskService
{
    /**
     * 成团后，更新 order 表中 enable_deliver_time 的时间
     * @inheritdoc
     */
    public function run($data)
    {
        ToolsAbstract::log(var_export($data,true),'order_enable_deliver_process.log');
        if(empty($data['pintuan_id'])){
            ToolsAbstract::log("pintuan_id不能为空",'order_enable_deliver_process.log');
            return 'pintuan_id不为空';
        }
        $pintuan_id = $data['pintuan_id'];
        $res = Pintuan::find()->select(['become_group_time'])->where(['id'=>$pintuan_id])->asArray()->one();
        if(empty($res)){
            ToolsAbstract::log("pintuan表 become_group_time 时间未找到",'order_enable_deliver_process.log');
            return "pintuan表 become_group_time 时间未找到";
        }
        $become_group_time = $res['become_group_time'];

        //修改 enable_deliver_time 字段的时间
        $pintuan_id = $data['pintuan_id'];
        $sql = "update `".Order::tableName()."` set enable_deliver_time='".$become_group_time
            ."' where id in (select order_id from `".OrderProduct::tableName()."` where pintuan_id = ".$pintuan_id.")";
        ToolsAbstract::log("sql: ".$sql,'order_enable_deliver_process.log');
        $res = \Yii::$app->orderDb->createCommand($sql)->execute();
        ToolsAbstract::log("update enable_deliver_time rows: ".var_export($res,true),'order_enable_deliver_process.log');
        return true;
    }
}