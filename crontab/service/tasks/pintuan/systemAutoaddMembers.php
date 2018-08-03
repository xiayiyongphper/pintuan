<?php

namespace service\tasks\pintuan;

use framework\components\ToolsAbstract;
use service\tasks\TaskService;
use common\models\pintuan\PintuanTask;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 15:09
 */
class systemAutoaddMembers extends TaskService
{
    const FINISH_TYPE = 3;
    const ROBOT_TYPE = 'system_autoadd_type';
    const ROBOT_TYPE_USE = 'system_autoadd_members'; // 机器人类型前是否选中，1为选中
    const ROBOT_TYPE_TIME_PARAM = 'system_autoadd_endtime'; // 比较的时间参数
    const ONCE_ROBOT_NUM = 1; // 每次增加的机器人数量

    public function run($data)
    {
        sleep(3);
        $date = date("Y-m-d H:i:s");
        $now_minute = date("Y-m-d H:i"); // 当前分钟数
        ToolsAbstract::log(__CLASS__ . '# now_minute: ' . print_r($now_minute, true), 'pintuan.log');
//        $now_minute = "2018-06-15 14:55"; // 当前分钟数
        $conditions = [
            "pintuan_task.system_autoadd_members" => 1,
            "pintuan_task.del" => 1,
            "pintuan_task.status" => 1,
            "pintuan_task.is_valid" => 1,
            "pintuan_task.".self::ROBOT_TYPE_USE => 1,
            "pintuan_task.".self::ROBOT_TYPE_TIME_PARAM => $now_minute
        ];
        $res = PintuanTask::find()->select(['pintuan_task.id as pintuan_task_id','pintuan_task.pintuan_id','pintuan_task.pintuan_activity_id','pintuan_task.pintuan_members',
            'pintuan_task.system_autoadd_endtime','pintuan_task.system_autoadd_endtime_nums','pintuan_task.pintuan_activity_endtime','pintuan.member_num','pintuan.id'])
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
                $flag = 0; // 加上num 人后,是否为已成团 ,0未成团,1为已成团
                if($val['system_autoadd_endtime'] > $val['pintuan_activity_endtime']){
                    ToolsAbstract::log(__CLASS__ . __METHOD__. '#create_robot: time pass -- ' .' -- success' , 'create_robots.log');
                    continue;
                }
                // 每一个发起的拼团，一次增加一个机器人
                if($val['pintuan_members'] > $val['member_num'] && $val['system_autoadd_endtime_nums'] > 0){
                    $num = self::ONCE_ROBOT_NUM;
                    if($val['pintuan_members'] - $val['member_num'] == 1){
                        $flag = 1;
                    }
                    $ret = PintuanTask::createRobotsBySystemsAutoAdd($val['pintuan_id'],$num,$val['pintuan_activity_endtime'],$val['system_autoadd_endtime'],$val['system_autoadd_endtime_nums'],$flag); // 给 pintuan_user 表的 pintuan_id 增加机器人
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