<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\store\v1;

use framework\components\ToolsAbstract;
use common\models\MarketConfigure;
use message\store\GuideJoinGroupReq;
use message\store\GuideJoinGroupRes;
use service\resources\StoreException;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * 支付完成后引导加群
 * Author: Jason Y. Wang
 * Class orderInfo
 * @package service\resources\store\v1
 */
class getGuideJoinMessage extends ResourceAbstract
{
    public function run($data)
    {
        $request = self::parseRequest($data);
        $response = self::response();
        $store_id =  $request->getStoreId();
        $joinMessage = MarketConfigure::getGuideJoinMessage($store_id);
        $response->setFrom($joinMessage);
        return $response;
    }

    public static function request()
    {
        return new GuideJoinGroupReq();
    }

    public static function response()
    {
        return new GuideJoinGroupRes();
    }
}
