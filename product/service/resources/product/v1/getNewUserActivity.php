<?php

namespace service\resources\product\v1;

use common\models\NewActProduct;
use common\models\NewActStore;
use common\models\NewUserActivity;
use message\product\NewUserActivityReq;
use message\product\NewUserActivityRes;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class getNewUserActivity
 * @package service\resources\product\v1
 */
class getNewUserActivity extends ResourceAbstract
{
    /** @var  NewUserActivityReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        $activity = NewUserActivity::getNewUserActivityOne($this->request->getCity());
        if (!$activity)
            return $this->response;

        if ($activity['place_type'] == 2) {
            $actStore = NewActStore::findOne([
                'act_id'   => $activity['id'],
                'store_id' => $this->request->getStoreId(),
                'del'      => NewActStore::NOT_DELETED,
            ]);
            if (!$actStore)
                return $this->response;
        } else {
            $actProduct = NewActProduct::findOne([
                'act_id'        => $activity['id'],
                'wholesaler_id' => $this->request->getWholesalerId(),
                'del'           => NewActProduct::NOT_DELETED,
            ]);
            if (!$actProduct)
                return $this->response;
        }

        $result['activity_id'] = $activity['id'];
        $this->response->setFrom(Tools::pb_array_filter($result));
        return $this->response;
    }

    public static function request()
    {
        return new NewUserActivityReq();
    }

    public static function response()
    {
        return new NewUserActivityRes();
    }
}