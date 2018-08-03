<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/7/2
 * Time: 19:59
 */

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanUser;
use framework\components\ToolsAbstract;
use message\common\BoolResponse;
use message\product\AddPintuanUserReq;
use service\resources\ResourceAbstract;

/**
 * Class AddPintuanUser
 * @package service\resources\pintuan\v1
 */
class AddPintuanUser extends ResourceAbstract
{
    /** @var  AddPintuanUserReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $pintuanId = $this->request->getPintuanId();
        $userId = $this->request->getUserId();
        $nickName = $this->request->getNickName();
        $avatarUrl = $this->request->getAvatarUrl();
        ToolsAbstract::log($this->request->toArray(),'add_pintuan_user.log');

        $pintuanUser = PintuanUser::findOne(['pintuan_id' => $pintuanId , 'user_id' => $userId]);
        if(empty($pintuanUser)){
            $pintuanUser = new PintuanUser();
            $pintuanUser->pintuan_id = $pintuanId;
            $pintuanUser->user_id = $userId;
            $pintuanUser->nick_name = $nickName;
            $pintuanUser->avatar_url = $avatarUrl;
            $pintuanUser->created_at = date("Y-m-d H:i:s");

            $pintuanUser->save();
        }

        $pintuan = Pintuan::findOne(['id' => $pintuanId]);
        if(!empty($pintuan)){
            $pintuan->member_num += 1;
            $pintuan->save();
        }

        $this->response->setFrom(['result' => true]);
        return $this->response;
    }

    public static function request()
    {
        return new AddPintuanUserReq();
    }

    public static function response()
    {
        return new BoolResponse();
    }
}