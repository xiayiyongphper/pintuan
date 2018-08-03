<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/31 0031
 * Time: 15:21
 */

namespace backend\controllers;

use backend\models\Page;
use backend\models\PintuanUser;
use yii\base\Controller;
use yii\data\Pagination;
use yii\filters\VerbFilter;


class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $req = \Yii::$app->request;
        $phone = $req->get('phone', '');
        $has_order = $req->get('has_order', 0);

        $query = PintuanUser::find();

        if ($phone) {
            $query->where(['like', 'phone', $phone])->orWhere(['like', 'nickName', $phone]);
        }

        if ($has_order) {
            $query->andWhere(['has_order' => $has_order]);
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 10]);
        $data = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        if ($data) {
            $curregion = new \backend\models\Region();
            foreach ($data as $key => $value) {
                //查询城市名称
                $data[$key]['province_name']      = $curregion::findName($value['province']) ;
                $data[$key]['city_name']      = $curregion::findName($value['city']) ;
                //$data[$key]['district_name'] = $curregion::findName($value['district']);
            }
        }
        return $this->render('index', ['res' => $data, 'pages' => $pages, 'phone' => $phone, 'has_order' => $has_order]);

    }



    public function actionSearch()
    {
        $req = \Yii::$app->request;
        $phone = $req->get('phone', '');
        $has_order = $req->get('has_order', 0);

        $query = PintuanUser::find();

        if ($phone) {
            $query->where(['like', 'phone', $phone])->orWhere(['like', 'nickName', $phone]);
        }

        if ($has_order) {
            $query->andWhere(['has_order' => $has_order]);
        }
        $countQuery = clone $query;
        echo $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 10]);
        $data = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('index', ['res' => $data, 'pages' => $pages, 'phone' => $phone, 'has_order' => $has_order]);
    }



    public function actionStores()
    {
        $id = \Yii::$app->request->get('id');
        $userStore = new \backend\models\PintuanUserStore();
        $userStores = $userStore->getList($id);
        $stores = [];
        $model = new \backend\models\Store();
        foreach ($userStores as $store) {
            $stores[] = $model::getInfo($store['store_id']);
        }
        echo json_encode($stores);
        exit;

    }

    public function actionStoreown()
    {
        $id = \Yii::$app->request->get('id');
        $userStore = new \backend\models\PintuanUserStore();
        $userStores = $userStore->getList($id);
        $stores = [];
        $model = new \backend\models\Store();
        $region = new \backend\models\Region();
        foreach ($userStores as $store) {
            $data = $model::getInfo($store['store_id']);
            array_push($stores, [
                'id' => $data['id'],
                'user_id' => $id,
                'province' => $region::findName($data['province']),
                'city' => $region::findName($data['city']),
                'address' => $data['detail_address']
            ]);
        };
        echo json_encode($stores);
        exit;
    }

    public function actionCancelstore()
    {
        $uid = \Yii::$app->request->get('uid');
        $user = \backend\models\PintuanUser::find()->where(['id' => $uid])->one();
        $user->setAttribute('own_store_id', 0);
        if ($user->save()) {
            echo json_encode(array('msg' => 'success', 'code' => 0));
            exit;
        }
        echo json_encode(array('msg' => 'failed', 'code' => 1));
        exit;

    }

    public function actionSavestore()
    {
        $req = \Yii::$app->request;
        $store_id = $req->get('id');
        $uid = $req->get('uid');
        $user = \backend\models\PintuanUser::find()->where(['id' => $uid])->one();
        $user->setAttribute('own_store_id', $store_id);
        if ($user->save()) {
            echo json_encode(array('msg' => 'success', 'code' => 0));
            exit;
        }
        echo json_encode(array('msg' => 'failed', 'code' => 1));
        exit;

    }
}