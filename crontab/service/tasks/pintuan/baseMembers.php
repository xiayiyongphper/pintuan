<?php

namespace service\tasks\pintuan;

use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\models\pintuan\PintuanTask;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 15:09
 */
class baseMembers extends TaskService
{
    const FINISH_TYPE = 3;
    const ROBOT_TYPE = 'base_members_type';
    const ROBOT_TYPE_USE = 'base_members'; // 机器人类型前是否选中，1为选中
    const ROBOT_TYPE_TIME_PARAM = 'base_members_aftertime'; // 比较的时间参数

    public function run($data)
    {
        sleep(6);
        $date = date("Y-m-d H:i:s");
        $now_minute = date("Y-m-d H:i"); // 当前分钟数
        ToolsAbstract::log(__CLASS__ . '# now_minute: ' . print_r($now_minute, true), 'pintuan.log');
//        $now_minute = "2018-06-15 10:06"; // 当前分钟数
        $conditions = [
            "pintuan_task.base_members" => 1,
            "pintuan_task.del" => 1,
            "pintuan_task.status" => 1,
            "pintuan_task.is_valid" => 1,
            "pintuan_task.".self::ROBOT_TYPE_USE => 1,
            "pintuan_task.".self::ROBOT_TYPE_TIME_PARAM => $now_minute
        ];
        $res = PintuanTask::find()->select(['pintuan_task.id as pintuan_task_id','pintuan_task.pintuan_id','pintuan_task.pintuan_activity_id','pintuan_task.pintuan_members',
            'pintuan_task.base_members_aftertime_active','pintuan.member_num','pintuan.id'])
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
                // 每一个发起的拼团，在当前分钟时，要插入的机器人个数,在 pintuan_members 大于base_members_aftertime_active的情况下
                //   base_members_aftertime_active - member_num
                if($val['pintuan_members'] > $val['base_members_aftertime_active'] && $val['base_members_aftertime_active'] > $val['member_num']){
                    $num = $val['base_members_aftertime_active'] - $val['member_num']; // 要增加的机器人数
                    $ret = PintuanTask::createRobots($val['pintuan_id'],$num,1); // 给 pintuan_user 表的 pintuan_id 增加机器人
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