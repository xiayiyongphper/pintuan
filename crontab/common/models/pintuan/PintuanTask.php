<?php

namespace common\models\pintuan;

use framework\components\ToolsAbstract;
use Yii;
use framework\db\ActiveRecord;
use common\models\pintuan\User;
use common\models\pintuan\Pintuan;
use common\models\pintuan\PintuanUser;


class PintuanTask extends ActiveRecord
{

    const EXTRA_ROBOT = 1;
    const FINISH_TYPE = 3; // 已完成
    const DOING_TYPE = 2; // 执行中
    const HAND_FINISH_DONE = 3; // 手动结束已执行
    const AUTO_FINISH_DONE = 2; // 拼团自动结束已执行

    private  static  $robot_type = [
        '1'=>'base_members_type',
        '2'=>'system_autoadd_type',
        '3'=>'promise_group_type'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pintuan_task';
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
    }

    /**
     *  关联pintuan表
     */
    public function getPintuan()
    {
        // hasOne要求返回两个参数 第一个参数是关联表的类名 第二个参数是两张表的关联关系
        // 这里uid是auth表关联id, 关联user表的uid id是当前模型的主键id
        return $this->hasOne(Pintuan::className(), ['id' => 'pintuan_id']);
    }


    /**
     * 给 pintuan_user 表的 pintuan_id 创建 $num 个机器人
     * $pintuan_id pintuan_user表的 pintuan_id
     * $num int
     * $type 创建机器人的类型 1表示base_members_type，3表示promise_group_type
     */
    public static function createRobots($pintuan_id,$num = 1,$robotType=1){
        if(!array_key_exists($robotType,self::$robot_type)){
            return false;
        }
        $robotTypeVal = self::$robot_type[$robotType];
        $res = self::getNumRobotInfos($pintuan_id,$num);
        if(empty($res)){
            ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#not found proper robot", 'pintuan.log');
            return false;
        }
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#res" . print_r($res, 1), 'pintuan.log');

        $now_date = date("Y-m-d H:i:s"); // 当前时间
        $insertData = [];
        for($i=0;$i<count($res);$i++){
            $insertData[$i]['pintuan_id'] = $pintuan_id;
            $insertData[$i]['user_id'] = $res[$i]['id'];
            $insertData[$i]['nick_name'] = $res[$i]['nick_name'];
            $insertData[$i]['avatar_url'] = $res[$i]['avatar_url'];
            $insertData[$i]['created_at'] = $now_date;
        }
        $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务

        $res_pintuan_user = Yii::$app->productDb->createCommand()->batchInsert(PintuanUser::tableName(), ['pintuan_id','user_id','nick_name','avatar_url','created_at'],$insertData)->execute();
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#insertData" . print_r($insertData, 1), 'pintuan.log');

        if($robotType == 3){ // 3为保证成团
            $update_pintuan_arr =['member_num'=> new \yii\db\Expression("member_num + $num"),'become_group_status'=>2,'become_group_time'=>$now_date];
        }else{
            $update_pintuan_arr =['member_num'=> new \yii\db\Expression("member_num + $num")];
        }

        // 更新 pintuan 表的 pintuan_id 对应的 member_num
        $res_pintuan = Pintuan::updateAll($update_pintuan_arr,['id'=>$pintuan_id]);
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# updateAll" . print_r($res, 1), 'pintuan.log');

        // 更新 $robotTypeVal 为3 已完成
        $res_pintuan_task = self::updateAll([$robotTypeVal=> self::FINISH_TYPE,'update_at'=>$now_date],['pintuan_id'=>$pintuan_id]);


        if($res_pintuan_user && $res_pintuan && $res_pintuan_task){
            $transaction->commit();
            if($robotType == 3){
                ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# robotType" . print_r($robotType, 1), 'wjqRabbitmq.log');
                self::sendNotifyBecomeGroup($pintuan_id);
            }
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 系统自动增加人数 的脚本增加机器人
     * 给 pintuan_user 表的 pintuan_id 创建 $num 个机器人
     * $pintuan_id pintuan_user表的 pintuan_id
     * $num int
     * $pintuan_activity_endtime date("Y-m-d H:i:s") 拼团活动结束时间
     * $system_autoadd_endtime date("Y-m-d H:i") 拼团系统增加时间(分钟)
     * $system_autoadd_endtime_nums  拼团系统每次增加多少分钟,一般为整数
     * $flag int 是否为已成团 ,0未成团,1为已成团
     */
    public static function createRobotsBySystemsAutoAdd($pintuan_id,$num,$pintuan_activity_endtime,$system_autoadd_endtime,$system_autoadd_endtime_nums,$flag = 0){
        $res = self::getNumRobotInfos($pintuan_id,$num);
        if(empty($res)){
            ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#not found proper robot", 'pintuan.log');
            return false;
        }
        $now_date = date("Y-m-d H:i:s"); // 当前时间
        $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务
        $insertData = [];
        for($i=0;$i<count($res);$i++){
            $insertData[$i]['pintuan_id'] = $pintuan_id;
            $insertData[$i]['user_id'] = $res[$i]['id'];
            $insertData[$i]['nick_name'] = $res[$i]['nick_name'];
            $insertData[$i]['avatar_url'] = $res[$i]['avatar_url'];
            $insertData[$i]['created_at'] = $now_date;
        }
        $res_pintuan_user = Yii::$app->productDb->createCommand()->batchInsert(PintuanUser::tableName(), ['pintuan_id','user_id','nick_name','avatar_url','created_at'],$insertData)->execute();
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#insertData" . print_r($insertData, 1), 'pintuan.log');


        if($flag == 1){ // 1为加num后已成团
            $update_pintuan_arr =['member_num'=> new \yii\db\Expression("member_num + $num"),'become_group_status'=>2,'become_group_time'=>$now_date];
        }else{
            $update_pintuan_arr =['member_num'=> new \yii\db\Expression("member_num + $num")];
        }
        // 更新 pintuan 表的 pintuan_id 对应的 member_num
        $res_pintuan = Pintuan::updateAll($update_pintuan_arr,['id'=>$pintuan_id]);


        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# updateAll" . print_r($res, 1), 'pintuan.log');

        // $pintuan_activity_endtime,$system_autoadd_endtime,$system_autoadd_endtime_nums ,先加,再比较时间
        $pintuan_activity_endtime_after = date("Y-m-d H:i",strtotime("+ $system_autoadd_endtime_nums minutes",strtotime($system_autoadd_endtime)));
        if($pintuan_activity_endtime_after >= substr($pintuan_activity_endtime,0,-3)){  // 去掉后面的3个字符,转化为 Y-m-d H:i 后再比较
            // 改为 已完成
            $res_pintuan_task = self::updateAll(['system_autoadd_type' => self::FINISH_TYPE,'system_autoadd_endtime'=>$pintuan_activity_endtime_after,'update_at'=>$now_date],['pintuan_id'=>$pintuan_id]);
        }else{
            // 改为 执行中
            $res_pintuan_task = self::updateAll(['system_autoadd_type' => self::DOING_TYPE,'system_autoadd_endtime'=>$pintuan_activity_endtime_after,'update_at'=>$now_date],['pintuan_id'=>$pintuan_id]);
        }

        if($res_pintuan_user && $res_pintuan && $res_pintuan_task){
            $transaction->commit();
            if($flag == 1) {
                self::sendNotifyBecomeGroup($pintuan_id);
            }
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }



    /**
     * 手动结束时给相应的未结束的(member_num 没有满)拼团活动,补上对应的机器人
     * 给 pintuan_user 表的 pintuan_id 创建 $num 个机器人
     * $pintuan_id pintuan_user表的 pintuan_id
     * $num int
     */
    public static function handFinishCreateRobots($pintuan_id,$num = 1){
        $res = self::getNumRobotInfos($pintuan_id,$num);
        if(empty($res)){
            ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#not found proper robot", 'pintuan.log');
            return false;
        }
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#res" . print_r($res, 1), 'pintuan.log');

        $now_date = date("Y-m-d H:i:s"); // 当前时间
        $insertData = [];
        for($i=0;$i<count($res);$i++){
            $insertData[$i]['pintuan_id'] = $pintuan_id;
            $insertData[$i]['user_id'] = $res[$i]['id'];
            $insertData[$i]['nick_name'] = $res[$i]['nick_name'];
            $insertData[$i]['avatar_url'] = $res[$i]['avatar_url'];
            $insertData[$i]['created_at'] = $now_date;
        }
        $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务

        $res_pintuan_user = Yii::$app->productDb->createCommand()->batchInsert(PintuanUser::tableName(), ['pintuan_id','user_id','nick_name','avatar_url','created_at'],$insertData)->execute();
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#insertData" . print_r($insertData, 1), 'pintuan.log');

        // 更新 pintuan 表的 pintuan_id 对应的 member_num
        $res_pintuan = Pintuan::updateAll(['member_num'=> new \yii\db\Expression("member_num + $num"),'become_group_status'=>2,'become_group_time'=>$now_date],['id'=>$pintuan_id]);
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# updateAll" . print_r($res, 1), 'pintuan.log');

        // 更新 $robotTypeVal 为3 已完成
        $res_pintuan_task = self::updateAll(["status"=> self::HAND_FINISH_DONE,'update_at'=>$now_date],['pintuan_id'=>$pintuan_id]);


        if($res_pintuan_user && $res_pintuan && $res_pintuan_task){
            $transaction->commit();
            self::sendNotifyBecomeGroup($pintuan_id);
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }


    /**
     * 系统自动结束时给相应的未结束的(member_num 没有满)拼团活动,补上对应的机器人
     * 给 pintuan_user 表的 pintuan_id 创建 $num 个机器人
     * $pintuan_id pintuan_user表的 pintuan_id
     * $num int
     */
    public static function createRobotsEndAutoAdd($pintuan_id,$num = 1){
        $res = self::getNumRobotInfos($pintuan_id,$num);
        if(empty($res)){
            ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#not found proper robot", 'pintuan.log');
            return false;
        }

        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#res" . print_r($res, 1), 'pintuan.log');
        $now_date = date("Y-m-d H:i:s"); // 当前时间
        $insertData = [];
        for($i=0;$i<count($res);$i++){
            $insertData[$i]['pintuan_id'] = $pintuan_id;
            $insertData[$i]['user_id'] = $res[$i]['id'];
            $insertData[$i]['nick_name'] = $res[$i]['nick_name'];
            $insertData[$i]['avatar_url'] = $res[$i]['avatar_url'];
            $insertData[$i]['created_at'] = $now_date;
        }
        $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务

        $res_pintuan_user = Yii::$app->productDb->createCommand()->batchInsert(PintuanUser::tableName(), ['pintuan_id','user_id','nick_name','avatar_url','created_at'],$insertData)->execute();
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."#insertData" . print_r($insertData, 1), 'pintuan.log');

        // 更新 pintuan 表的 pintuan_id 对应的 member_num
        $res_pintuan = Pintuan::updateAll(['member_num'=> new \yii\db\Expression("member_num + $num"),'become_group_status'=>2,'become_group_time'=>$now_date],['id'=>$pintuan_id]);
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# updateAll" . print_r($res, 1), 'pintuan.log');

        $res_pintuan_task = self::updateAll(["status"=> self::AUTO_FINISH_DONE,'update_at'=>$now_date],['pintuan_id'=>$pintuan_id]);

        if($res_pintuan_user && $res_pintuan && $res_pintuan_task){
            $transaction->commit();
            self::sendNotifyBecomeGroup($pintuan_id);
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }


    /**
     * 获取pintuan_id 下不用的user_id 对应的num 个机器人的 'id','nick_name','avatar_url'
     * @param $pintuan_id
     * @param $num
     * @return array
     */
    private static function getNumRobotInfos($pintuan_id,$num){
        if($num > 10000 || $num < 1){
            return [];
        }
        $userIdArr = [];
        // 先查询 pintuan_id 对应的 有多少不同的user_id,并连接起来
        $resPintuanUser = PintuanUser::find()->select(['user_id'])->where(['pintuan_id'=>$pintuan_id])->asArray()->all();
        if(!empty($resPintuanUser)){ // 有人参团了
            foreach ($resPintuanUser as $key => $val){
                $userIdArr[$key] = $val['user_id'];
            }
        }
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# PintuanUser_find" . print_r($userIdArr, 1)." num :".$num, 'pintuan.log');
        $conditions = [
            "del" => 1,
            "is_robot" => 2
        ];
        $res = User::find()->select(['id','nick_name','avatar_url'])->where($conditions);
        if(!empty($userIdArr)){
            $res = $res->andWhere(['not in','id',$userIdArr]);
        }
        $res = $res->asArray()->OrderBy(" rand() ")->limit($num)->all();
        return $res;
    }

    /**
     * 生成数据到mq ,消息处理的时候 ,更新order表pintuan_id 对应的  enable_deliver_time(达到发货条件的时间) 为 当前date("Y-m-d H:i:s")
     * 如果已成团,发送异步消息到mq
     */
    public static function sendNotifyBecomeGroup($pintuan_id){
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# begin; " . print_r($pintuan_id, 1), 'wjqRabbitmq.log');
        $rabbitMq = ToolsAbstract::getRabbitMq();
        //发布更新order 表的 enable_deliver_time 字段的消息
        $data = [
            'route' => 'taskOrder.orderEnableDeliverProcess',
            'params' => [
                'pintuan_id' => $pintuan_id
            ],
        ];
        ToolsAbstract::log(__CLASS__ . '#'. __METHOD__."# data" . print_r($data, 1), 'wjqRabbitmq.log');
        $rabbitMq->publish($data);
    }
}
