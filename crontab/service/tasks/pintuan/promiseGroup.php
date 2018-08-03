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
class PromiseGroup extends TaskService
{
    const FINISH_TYPE = 3;
    const ROBOT_TYPE = 'promise_group_type';
    const ROBOT_TYPE_USE = 'promise_group'; // 机器人类型前是否选中，1为选中
    const ROBOT_TYPE_TIME_PARAM = 'promise_group_endtime'; // 比较的时间参数

    public function run($data)
    {

        sleep(12);
        $date = date("Y-m-d H:i:s");
        $now_minute = date("Y-m-d H:i"); // 当前分钟数
        ToolsAbstract::log(__CLASS__ . '# now_minute: ' . print_r($now_minute, true), 'pintuan.log');
//        $now_minute = "2018-06-14 18:21"; // 当前分钟数
        $conditions = [
            "pintuan_task.promise_group" => 1,
            "pintuan_task.del" => 1,
            "pintuan_task.status" => 1,
            "pintuan_task.is_valid" => 1,
            "pintuan_task.".self::ROBOT_TYPE_USE => 1,
            "pintuan_task.".self::ROBOT_TYPE_TIME_PARAM=> $now_minute
        ];
        $res = PintuanTask::find()->select(['pintuan_task.id as pintuan_task_id','pintuan_task.pintuan_id','pintuan_task.pintuan_activity_id','pintuan_task.pintuan_members',
                                            'pintuan.member_num','pintuan.id'])
            ->joinWith(['pintuan'])
            ->where($conditions)
            ->andWhere(['<>','pintuan_task.'.self::ROBOT_TYPE,self::FINISH_TYPE])
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
                    $ret = PintuanTask::createRobots($val['pintuan_id'],$num,3); // 给 pintuan_user 表的 pintuan_id 增加机器人 ，3表示保证成团
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