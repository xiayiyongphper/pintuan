<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\User;
use message\user\getRandUserRequest;
use message\user\getRandUserResponse;
use service\resources\ResourceAbstract;
use service\tools\Tools;
use yii\db\Expression;

class getRandUser extends ResourceAbstract
{
    public function run($data)
    {
        //SELECT * FROM user WHERE id >= ((SELECT MAX(id) FROM user) * RAND() - 100) LIMIT 100
        $expression = new Expression('((SELECT MAX(id) FROM user) * RAND() - 100)');
        $users = User::find()
            ->select(['user_id' => 'id', 'avatar_url', 'nick_name'])
            ->where(['>', 'id', $expression])
            ->andWhere(['!=', 'avatar_url', ''])
            ->andWhere(['!=', 'nick_name', ''])
            ->limit(100)
            ->asArray()
            ->all();

        shuffle($users);
        $response = self::response();
        Tools::log($users, 'getRandUser.log');
        $response->setFrom(['users' => $users]);
        Tools::log($response->toArray(), 'getRandUser.log');
        return $response;

    }

    public static function request()
    {
        return new getRandUserRequest();
    }

    public static function response()
    {
        return new getRandUserResponse();
    }

}