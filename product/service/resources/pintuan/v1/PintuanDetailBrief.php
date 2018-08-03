<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanUser;
use message\product\PintuanDetailReq;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 拼团详情简化版，内部调用
 */
class PintuanDetailBrief extends ResourceAbstract
{
    /** @var  PintuanDetailReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();
        $user_id = $this->request->getUserId();
        $pintuan_id = $this->request->getPintuanId();
        Tools::log($pintuan_id,'PintuanDetailBrief.log');
        Tools::log($user_id,'PintuanDetailBrief.log');
        if(!$pintuan_id || !$user_id){
            return $response;
        }

        /** @var \message\product\Pintuan $response */
        $pintuan = Pintuan::findOne(['id' => $pintuan_id, 'status' => 1, 'del' => 1]); //有效团

        if (!$pintuan) {
            return $response;
        }

        $pintuanUser = PintuanUser::find()
            ->where(['pintuan_id' => $pintuan_id])
            ->andWhere(['user_id' => $user_id])->exists();
        Tools::log($pintuanUser,'PintuanDetailBrief.log');
        //用户没有参与这个团
        if(!$pintuanUser){
            return $response;
        }

        $response->setId($pintuan->id);
        $response->setPintuanActivityId($pintuan->pintuan_activity_id);
        $response->setMemberNum($pintuan->member_num);
        $response->setStatus($pintuan->status);
        $response->setBecomeGroupStatus($pintuan->become_group_status);
        $response->setBecomeGroupTime($pintuan->become_group_time);
        $response->setJoinThisPintuan(1); //用户参与了这个团

        return $response;
    }

    public static function request()
    {
        return new PintuanDetailReq();
    }

    public static function response()
    {
        return new \message\product\Pintuan();
    }
}