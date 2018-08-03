<?php
/**
 * Created by crontab.
 * User: Ryan Hong
 * Date: 2018/7/12
 * Time: 16:48
 */

namespace service\tasks\pintuan;

use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\pintuan\Pintuan;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * Class pintuanBecomeGroupProcess
 * @package service\tasks\pintuan
 */
class pintuanBecomeGroupProcess extends TaskService
{

    /**
     * @param mixed $data
     * @return mixed 如果不成功请抛异常；其他情况都是认为是成功的。
     */
    public function run($data)
    {
        ToolsAbstract::log($data,'pintuan_become_group_process.log');
        if(empty($data['pintuan_id'])){
            throw new \Exception("参数格式错误");
        }

        $pintuanId = $data['pintuan_id'];
        $pintuan = Pintuan::findOne(['id' => $pintuanId]);
        if(!$pintuan){
            throw new \Exception("拼团未找到");
        }

        //更新所以给拼团的订单的 enable_deliver_time
        $sql = "update `".Order::tableName()."` set enable_deliver_time='".$pintuan->become_group_time
            ."' where id in (select order_id from `".OrderProduct::tableName()."` where pintuan_id = ".$pintuanId.")";
        ToolsAbstract::log($sql,'pintuan_become_group_process.log');

        $res = Order::getDb()->createCommand($sql)->execute();

        return $res;
    }
}