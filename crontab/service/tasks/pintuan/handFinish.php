<?php

namespace service\tasks\pintuan;

use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\models\pintuan\PintuanTask;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 11:09
 */
class HandFinish extends TaskService
{

    const HAND_FINISH = 2; // 手动已结束

    public function run($data)
    {
        sleep(8);
        // 查询 在相应时间内，手动已结束（status=2）,del=1 ，的拼团活动
        $date = date("Y-m-d H:i:s");

        $conditions = [
            "pintuan_task.del" => 1,
            "pintuan_task.is_valid" => 1,
            "pintuan_task.status" => self::HAND_FINISH,
        ];
        $res = PintuanTask::find()->select(['pintuan_task.id as pintuan_task_id','pintuan_task.pintuan_id','pintuan_task.pintuan_activity_id','pintuan_task.pintuan_members',
                                            'pintuan.member_num','pintuan.id'])
            ->joinWith(['pintuan'])
            ->where($conditions)
            ->andWhere(['<=','pintuan_task.'.'pintuan_activity_starttime',$date])
            ->andWhere(['>=','pintuan_task.'.'pintuan_activity_endtime',$date]);
         // 输出SQL语句
        $commandQuery = clone $res;
        $sql = $commandQuery->createCommand()->getRawSql();
        $res = $res->asArray()->all();

        ToolsAbstract::log(__CLASS__ . '#' . print_r($res, true), 'pintuan.log');
        if(!empty($res)){
            ToolsAbstract::log(__CLASS__ . '#sql : ' . $sql, 'pintuan_sql.log');
        }

        if(!empty($res)){
            foreach ($res as $val){
                // 每一个发起的拼团，在保证成团时，要插入的机器人个数  pintuan_members - member_num  总的需要拼团人数-已拼团人数
                if($val['pintuan_members'] > $val['member_num']){
                    $num = $val['pintuan_members'] - $val['member_num']; // 要增加的机器人数
                    $ret = PintuanTask::handFinishCreateRobots($val['pintuan_id'],$num);
                    if($ret == false){
                        ToolsAbstract::log(__CLASS__ . __METHOD__. '#create_robot: num -- '.$num .' -- failed' , 'create_robots.log');
                    }else{
                        ToolsAbstract::log(__CLASS__ . __METHOD__. '#create_robot: num -- '.$num .' -- success' , 'create_robots.log');
                    }
                }
            }
        }

        return true;
    }



}