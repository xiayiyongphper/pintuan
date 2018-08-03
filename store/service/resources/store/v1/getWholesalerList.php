<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\Wholesaler;
use common\models\WholesalerDistrict;
use framework\components\ToolsAbstract;
use message\store\WholesalerRequest;
use message\store\WholesalerResponse;
use service\resources\ResourceAbstract;

class getWholesalerList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var WholesalerRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $query = Wholesaler::find()->select('id as wholesaler_id, name as wholesaler_name');
        $query->andWhere(['status' => Wholesaler::REVIEW_PASSED, 'del' => Wholesaler::NOT_DELETED]);

        if ($request->getWholesalerId()) {
            $query->andWhere(['id' => $request->getWholesalerId()]);
        }

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