<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\Store;
use common\models\WholesalerDistrict;
use framework\components\ToolsAbstract;
use message\store\WholesalerRequest;
use message\store\WholesalerResponse;
use service\resources\ResourceAbstract;
use service\resources\StoreException;

class getWholesalerDistrictList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var WholesalerRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getStoreId()) {
            StoreException::invalidParam();
        }

        /** @var Store $store */
        $store = Store::find()->where(['id' => $request->getStoreId()])->asArray()->all();
        $district = array_column($store, 'district');
        $query = WholesalerDistrict::find()
            ->select(['wholesaler_id'])
            ->where([
                'district' => $district,
                'del'      => WholesalerDistrict::NOT_DELETED,
            ]);

        $wholesalers = $query->asArray()->all();

        $respData = [
            'wholesalers' => $wholesalers
        ];

        $response->setFrom(ToolsAbstract::pb_array_filter($respData));
        return $response;

    }

    public static function request()
    {
        return new WholesalerRequest();
    }

    public static function response()
    {
        return new WholesalerResponse();
    }

}