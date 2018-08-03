<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\Store;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\store\StoreRequest;
use message\store\StoreResponse;
use service\resources\ResourceAbstract;
use service\resources\StoreException;
use service\tools\Tools;
use yii\db\Expression;

class getStoreList extends ResourceAbstract
{
    const PAGE_SIZE = 10;

    public function run($data)
    {
        /** @var StoreRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $query = Store::find();

        $select = ['id as store_id', 'name as store_name', 'address', 'lat', 'lng'];

        if ($request->getLat() && $request->getLng()) {
            $lng = $request->getLng();
            $lat = $request->getLat();
            $select[] = new Expression('(ROUND(6378.138*2*ASIN(SQRT(POW(SIN(('
                . $lat . '*PI()/180-`lat`*PI()/180)/2),2)+COS('
                . $lat . '*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(('
                . $lng . '*PI()/180-`lng`*PI()/180)/2),2)))*1000)) as distance');


            if ($request->getStoreId()) {
                $query->andWhere(['not in', 'id', $request->getStoreId()]);
            }
            $query->andWhere(['status' => Store::REVIEW_PASSED, 'del' => Store::NOT_DELETED]);
            $query->orderBy('distance asc');
        } else {
            if ($request->getStoreId()) {
                $query->andWhere(['id' => $request->getStoreId()]);
            }
        }

        $query->select($select);

        if ($request->getPage()) {
            $totalCount = $query->count();
            $pageSize = $request->getPageSize() ?: self::PAGE_SIZE;

            $pagination = new Pagination(['totalCount' => $totalCount]);
            $pagination->setCurPage($request->getPage());
            $pagination->setPageSize($pageSize);
            $query->offset($pagination->getOffset())->limit($pageSize);

            $respData['pages'] = Tools::getPagination($pagination);
        }

//        Tools::log($query->createCommand()->getRawSql(), 'sql.log');

        $stores = $query->asArray()->all();

//        if (!$stores && $request->getStoreId()) {
//            StoreException::invalidParam();
//        }

        $respData['stores'] = $stores;

        $response->setFrom($respData);
        return $response;

    }

    public static function request()
    {
        return new StoreRequest();
    }

    public static function response()
    {
        return new StoreResponse();
    }

}