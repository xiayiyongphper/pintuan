<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanTask;
use message\product\PintuanChangeReq;
use message\product\PintuanChangeRes;
use message\product\PintuanStartReq;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 开团
 */
class PintuanChange extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanStartReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 开启事物
        // 根据自提点id查询自定义的自提点的活动id
        $pintuan = Pintuan::findOne(['id' => $request->getPintuanId(), 'status' => 2]);
        if (!$pintuan) {
            Exception::throwException(Exception::PINTUAN_NOT_FIND);
        }
        $pintuan->status = 1;// 更改为有效团
        if (!$pintuan->save()) {
            Tools::log($pintuan->errors, 'changePintuanError.log');
            Exception::throwException(Exception::PINTUAN_CHANGE_FAIL);
        }

        // 查询活动信息
        $activityModel = PintuanActivity::findOne(['id' => $pintuan->pintuan_activity_id]);

        // 同时写入拼团定时任务表 pintuan_task
//        "{"base_member_num":{"after_start_min":1,"member_num":9},"auto_increment":{"before_end_min":60,"increment_cycle_min":10},"fill_before_end":{"before_end_min":5}}"
        $strategy = json_decode($activityModel->strategy, true);
        if (!empty($strategy)) {
            $pintaunTask = new PintuanTask();
            $pintaunTask->pintuan_activity_id = $activityModel->id;
            $pintaunTask->pintuan_id = $pintuan->id;
            $pintaunTask->pintuan_members = $activityModel->member_num;
            $pintaunTask->continue_pintuan = $activityModel->continue_pintuan;
            $pintaunTask->base_members = isset($strategy['base_member_num']) ? 1 : 2;
            $pintaunTask->system_autoadd_members = isset($strategy['auto_increment']) ? 1 : 2;
            $pintaunTask->promise_group = isset($strategy['fill_before_end']) ? 1 : 2;
            if (isset($strategy['base_member_num']['after_start_min'])) {
                $pintaunTask->base_members_aftertime = date('Y-m-d H:i', strtotime($pintuan->create_at) + $strategy['base_member_num']['after_start_min'] * 60);
            }

            $pintaunTask->base_members_aftertime_active = isset($strategy['base_member_num']['member_num']) ? $strategy['base_member_num']['member_num'] : 0;

            if (isset($strategy['auto_increment']['before_end_min'])) {
                $pintaunTask->system_autoadd_endtime = date('Y-m-d H:i', strtotime($pintuan->end_time) - $strategy['auto_increment']['before_end_min'] * 60);
            }

            $pintaunTask->system_autoadd_endtime_nums = isset($strategy['auto_increment']['increment_cycle_min']) ? $strategy['auto_increment']['increment_cycle_min'] : 10;

            if (isset($strategy['fill_before_end']['before_end_min'])) {
                $pintaunTask->promise_group_endtime = date('Y-m-d H:i', strtotime($pintuan->end_time) - $strategy['fill_before_end']['before_end_min'] * 60);
            }
            $pintaunTask->pintuan_end_autoadd_time = date('Y-m-d H:i', strtotime($pintuan->end_time) + 1 * 60);
            $pintaunTask->pintuan_activity_starttime = $activityModel->start_time;
            $pintaunTask->pintuan_activity_endtime = $activityModel->end_time;//跟家琪确定过了，这里继续用活动的结束时间
            $pintaunTask->create_at = date('Y-m-d H:i:s');
            $pintaunTask->update_at = date('Y-m-d H:i:s');

            if (!$pintaunTask->validate() || !$pintaunTask->save()) {
                Exception::throwException(Exception::PINTUAN_OPERATION_TASK_FAILURE);
            }
        }

        $response->setFrom(['pintuan' => $pintuan->attributes]);
        return $response;
    }

    public static function request()
    {
        return new PintuanChangeReq();
    }

    public static function response()
    {
        return new PintuanChangeRes();
    }
}