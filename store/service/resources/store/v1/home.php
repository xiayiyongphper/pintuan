<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\Banner;
use common\models\Store;
use common\models\Topic;
use common\models\WholesalerDistrict;
use framework\components\ToolsAbstract;
use message\store\HomeResponse;
use message\store\Store as StoreReq;
use service\resources\ResourceAbstract;
use service\resources\StoreException;

class home extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getStoreId()) {
            StoreException::invalidParam();
        }

        $banners = Banner::find()
            ->select('id,title,img_url,type,value')
            ->where(['status' => 1])
            ->orderBy('sort asc, updated_at desc')
            ->asArray()->all();

        $topics = Topic::find()
            ->select('id,title,img_url,type,products')
            ->where(['status' => 1])
            ->orderBy('sort asc, updated_at desc')
            ->asArray()->all();

        $respData = [
            'banners' => $banners,
            'topics'  => $topics,
        ];

        $response->setFrom($respData);
        return $response;

    }

    public static function request()
    {
        return new StoreReq();
    }

    public static function response()
    {
        return new HomeResponse();
    }

}