<?php

namespace service\tasks\pintuan;

use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\models\pintuan\PintuanTask;

/**
 * 拼团自动结束定时执行脚本
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 15:09
 */
class PintuanEndAutoadd extends TaskService
{
    const FINISH_TYPE = 2;
    const ROBOT_TYPE = 'pintuan_end_autoadd_type';
    const ROBOT_TYPE_TIME_PARAM = 'pintuan_end_autoadd_time'; // 比较的时间参数

    public function run($data)
    {
        sleep(12);
        $now_minute = date("Y-m-d H:i"); // 当前分钟数
        ToolsAbstract::log(__CLASS__ . '# now_minute: ' . print_r($now_minute, true), 'pintuan.log');
//        $now_minute = "2018-06-15 14:55"; // 当前分钟数
        $conditions = [
            "pintuan_task.del" => 1,
            "pintuan_task.status" => 1,
            "pintuan_task.is_valid" => 1,
            "pintuan_task.promise_group_type" => 1,  // 保证成团任务类型 执行未完成
            "pintuan_task.".self::ROBOT_TYPE => 1,
            "pintuan_task.".self::ROBOT_TYPE_TIME_PARAM => $now_minute
        ];
        $res = PintuanTask::find()->select(['pintuan_task.id as pintuan_task_id','pintuan_task.pintuan_id','pintuan_task.pintuan_activity_id','pintuan_task.pintuan_members',
            'pintuan_task.'.self::ROBOT_TYPE_TIME_PARAM,'pintuan.member_num','pintuan.id'])
            ->joinWith(['pintuan'])
            ->where($conditions)
            ->andWhere(['<>','pintuan_task.'.self::ROBOT_TYPE,self::FINISH_TYPE]);
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
                // 结束时添加 $val['pintuan_members']-$val['member_num'] 个机器人
                if($val['pintuan_members'] > $val['member_num']){
                    $num = $val['pintuan_members']-$val['member_num'];
                    $ret = PintuanTask::createRobotsEndAutoAdd($val['pintuan_id'],$num); // 给 pintuan_user 表的 pintuan_id 增加机器人
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