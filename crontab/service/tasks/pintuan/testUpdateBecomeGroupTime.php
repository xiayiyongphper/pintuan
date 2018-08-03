<?php

namespace service\tasks\pintuan;

use common\models\order\Order;
use common\models\order\OrderProduct;
use common\models\pintuan\Pintuan;
use common\models\pintuan\PintuanActivity;
use common\models\pintuan\PintuanUser;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 15:09
 */
class testUpdateBecomeGroupTime extends TaskService
{

    public function run($data)
    {

        $res = Pintuan::find()->select(['pt.member_num as pt_member_num', 'pta.member_num as pta_member_num', 'pt.pintuan_activity_id','pt.id'])
            ->alias('pt')
            ->leftJoin(['pta' => PintuanActivity::tableName()],'pt.pintuan_activity_id = pta.id')
            ->where(['pt.del' => 1, 'pta.del' => 1])
            ->andWhere(['=', 'pt.become_group_status', 2])
            ->andWhere(['or',
                ['pt.become_group_time'=>null],
                ['=', 'pt.become_group_time', "0000-00-00 00:00:00"]]);

        // 输出SQL语句
        $commandQuery = clone $res;
        $sql = $commandQuery->createCommand()->getRawSql();
        ToolsAbstract::log(__CLASS__ . '# sql: ' . $sql, 'testUpdateBecomeGroupTime.log');
        $res = $res->orderBy('pt.id desc')->asArray()->all();
        ToolsAbstract::log(__CLASS__ . '# res: ' . var_export($res,true), 'testUpdateBecomeGroupTime.log');

        if(!empty($res)){
            foreach ($res as $val){
                $pt_member_num = (int)$val['pt_member_num'];
                $pta_member_num = (int)$val['pta_member_num'];
                if($pt_member_num >= $pta_member_num && $pta_member_num >1){ //需要修改字段
                    $pintuanUserInfo = PintuanUser::find()->select(['created_at'])->where(['pintuan_id'=>$val['id']])->orderBy('id asc')->limit(1)->offset($val['pta_member_num']-1)->asArray()->one();
                    ToolsAbstract::log(__CLASS__ . '# pintuan_id: '.$val['id'].'# pintuanUserInfo: ' . var_export($pintuanUserInfo,true), 'testUpdateBecomeGroupTime.log');
                    if(!empty($pintuanUserInfo)){
                        $pintuanUpdate = Pintuan::updateAll(['become_group_time'=>$pintuanUserInfo['created_at']],['id'=>$val['id']]);
                        ToolsAbstract::log(__CLASS__ . '# pintuanUpdate :' . $val['id'], 'testUpdateBecomeGroupTime.log');
                        if($pintuanUpdate){
                            $orderProductInfos = OrderProduct::find()->select(['order_id'])->where(['pintuan_id'=>$val['id']])->asArray()->all();
                            foreach ($orderProductInfos as $v){
                                Order::updateAll(['enable_deliver_time'=>$pintuanUserInfo['created_at']],['id'=>$v['order_id']]);
                                ToolsAbstract::log(__CLASS__ . '# orderUpdate :' . $v['order_id'], 'testUpdateBecomeGroupTime.log');
                            }
                        }
                    }
                }
            }
        }


        return true;
    }



}