<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\User;
use common\models\UserStore;
use framework\data\Pagination;
use message\user\UserListRes;
use message\user\UserStore as UserStorePb;
use service\resources\ResourceAbstract;
use service\resources\UserException;
use service\tools\Tools;

class getUserStoreNumList extends ResourceAbstract
{
    const PAGE_SIZE = 6;

    public function run($data)
    {
        /** @var UserStorePb $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserId() || !$request->getStoreId()) {
            UserException::invalidParams();
        }

        $query = UserStore::find()->where(['store_id' => $request->getStoreId()]);

        $respData = [];
        $totalCount = $query->count();
        $page = $request->getPage();
        $pageSize = $request->getPageSize() ?: self::PAGE_SIZE;
//        $robotNum = User::find()->where(['is_robot' => User::ROBOT])->count();
//        $robotNum = UserStore::FAKE_NUM > $robotNum ? $robotNum : UserStore::FAKE_NUM;
        $fakeCount = $totalCount + UserStore::FAKE_NUM;//$robotNum

        if ($page) {
            $pagination = new Pagination(['totalCount' => $fakeCount]);
            $pagination->setCurPage($page);
            $pagination->setPageSize($pageSize);
            $query->offset($pagination->getOffset())->limit($pageSize);

            $respData['pages'] = Tools::getPagination($pagination);
        } else {
            $page = 1;
            $query->limit($pageSize);
        }

        $query->orderBy('id desc');
        $userIds = $query->select('user_id')->column();
        $userCount = count($userIds);

        if (!$userCount || $userCount < $pageSize) {
            $beforeNum = ($page - 1) * $pageSize;
            if (isset($respData['pages']) && $respData['pages']['last_page'] == $page) {
                $pageSize = $fakeCount - $beforeNum;
            }
            if ($page == 1 || $page <= $respData['pages']['last_page']) {
                $fakeNum = $pageSize - $userCount;
                $fakeOffset = $beforeNum - $totalCount;
                $fakeOffset < 0 && $fakeOffset = 0;
                $fakeUsers = User::find()->select('id')
                    ->where(['is_robot' => User::ROBOT])
                    ->orderBy('id desc')
                    ->offset($fakeOffset)
                    ->limit($fakeNum);

//                Tools::log($fakeUsers->createCommand()->getRawSql(), 'sql.log');
                $fakeUsers = $fakeUsers->column();
                $userIds = array_merge($userIds, $fakeUsers);
            }
        }

        $users = [];
        if ($userIds) {
            $users = User::find()->select('id as user_id, nick_name, avatar_url')
                ->where(['id' => $userIds])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $userIds) . ')')])
                ->asArray()->all();
        }

        $respData['users'] = $users;
        $response->setFrom($respData);

        return $response;
    }

    public static function request()
    {
        return new UserStorePb();
    }

    public static function response()
    {
        return new UserListRes();
    }

}