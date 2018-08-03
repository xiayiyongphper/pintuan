<?php
/**
 * Created by user.
 * User: Ryan Hong
 * Date: 2018/7/16
 * Time: 17:51
 */

namespace service\resources\user\v1;

use common\models\User;
use framework\components\ToolsAbstract;
use message\user\UserListReq;
use message\user\UserListRes;
use service\resources\ResourceAbstract;

/**
 * Class getUserList
 * @package service\resources\user\v1
 */
class getUserList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserListReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $users = [];
        $userIds = $request->getUserIds();
        if(!empty($userIds)){
            $query = User::find()
                ->select('id as user_id,nick_name,gender,avatar_url,country,province,city,birthday,constellation,signature,is_robot')
                ->where(['id' => $request->getUserIds(), 'del' => User::NOT_DELETED]);

            $users = $query->asArray()->all();
        }

        $response->setFrom(['users' => $users]);
        return $response;
    }

    public static function request()
    {
        return new UserListReq();
    }

    public static function response()
    {
        return new UserListRes();
    }
}