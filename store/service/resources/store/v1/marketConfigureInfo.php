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
use message\common\MarketconfigureReq;
use message\common\MarketconfigureRes;
use service\resources\StoreException;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderInfo
 * @package service\resources\store\v1
 */
class marketConfigureInfo extends ResourceAbstract
{
    public function run($data)
    {
        $request = self::parseRequest($data);
        $response = self::response();
        $type =  $request->getType() ;
        if ($type == 1) {
            $select = 'custom_nickname,custom_qrcode';
        } else if ($type == 2) {
            $select = 'solitaire_success_msg,invite_btn_msg';
        } else if ($type == 3) {
            $select = 'invite_colonel_banner,colonel_describe_img';
        } else {
            //默认
            $select = 'custom_nickname,custom_qrcode';
        }

        $configure = MarketConfigure::find()->select($select)->asArray()->one();
        if (!$configure) {
            StoreException::throwNewException(StoreException::MARKET_CONFIGURE_NOT_EXIST);
        }
        $response->setFrom($configure);
        return $response;
    }

    public static function request()
    {
        return new MarketconfigureReq();
    }

    public static function response()
    {
        return new MarketconfigureRes();
    }
}
