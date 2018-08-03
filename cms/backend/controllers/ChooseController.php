<?php

namespace backend\controllers;

use common\components\RabbitMQ;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * 弹框选择数据的专用控制器
 * Class ChooseController
 * @package backend\controllers
 */
class ChooseController extends Controller
{
    /**
     * 测试控制器
     * {@inheritdoc}
     */
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $data = [];
        return $this->render('index', $data);
    }

    /**
     * 选择商品规格
     * @return string
     */
    public function actionGoodspe()
    {
        $this->layout = false;
        $data = [];

        $get = yii::$app->request->get();
        $name = isset($get['name'])? trim($get['name']) : '';
        $third_category_id = isset($get['third_category_id'])? intval($get['third_category_id']) : 0;
        $wholesaler_id = isset($get['wholesaler_id'])? intval($get['wholesaler_id']) : 0;
        $data['name'] = $name;
        $data['third_category_id'] = $third_category_id;
        $data['wholesaler_id'] = $wholesaler_id;

        //获取供货商
        $wholesalerModel = new \backend\models\Wholesaler();
        $where = [
            'del'=>1
        ];
        $wholesaler = $wholesalerModel::find()->where($where)->asArray()->all();
        $data['wholesaler'] = $wholesaler;

        //获取商品分类
        $categoryModel = new \backend\models\Category();
        $where = [
            'level'=>3,
            'del'=>1
        ];
        $third_category = $categoryModel::find()->where($where)->asArray()->all();
        $data['third_category'] = $third_category;

        return $this->render('goodspe', $data);
    }

    /**
     * 选择商品
     * @return string
     */
    public function actionGood()
    {
        $this->layout = false;
        $data = [];

        $get = yii::$app->request->get();
        $name = isset($get['name'])? trim($get['name']) : '';
        $third_category_id = isset($get['third_category_id'])? intval($get['third_category_id']) : 0;
        $wholesaler_id = isset($get['wholesaler_id'])? intval($get['wholesaler_id']) : 0;
        $data['name'] = $name;
        $data['third_category_id'] = $third_category_id;
        $data['wholesaler_id'] = $wholesaler_id;

        //获取供货商
        $wholesalerModel = new \backend\models\Wholesaler();
        $where = [
            'del'=>1
        ];
        $wholesaler = $wholesalerModel::find()->where($where)->asArray()->all();
        $data['wholesaler'] = $wholesaler;

        //获取商品分类
        $categoryModel = new \backend\models\Category();
        $where = [
            'level'=>3,
            'del'=>1
        ];
        $third_category = $categoryModel::find()->where($where)->asArray()->all();
        $data['third_category'] = $third_category;

        return $this->render('good', $data);
    }


    /**
     * ajax获取商品规格
     * @return array
     */
    public function actionGetgoodspelist()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Specification();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $name =  isset($keys['name'])? $keys['name'] : '';

        $where = [
            'product.status'=>1,
            'product.del'=>1,
            'specification.del'=>1
        ];
        $andWhere = [];
        $name = trim($name);
        if ($name) {
            $andWhere = [
                'like', 'product.name', $name
            ];
        }

        $third_category_id =  isset($keys['third_category_id'])?  intval($keys['third_category_id']) : 0;
        $wholesaler_id =  isset($keys['wholesaler_id'])? intval($keys['wholesaler_id']) : 0;

        if ($third_category_id) {
            $where['product.third_category_id'] = $third_category_id;
        }

        if ($wholesaler_id) {
            $where['product.wholesaler_id'] = $wholesaler_id;
        }

        $conut =$model::find()->join('LEFT JOIN','product','product.id=specification.product_id')->where($where)->andWhere($andWhere)->count();

        $offset = $limit * ($page - 1);
        $select = 'specification.product_id,specification.id,specification.price,product.name,product.wholesaler_id,specification.item_detail';
        $data = $model::find()->where($where)->andWhere($andWhere)->select($select)
            ->leftJoin('product','product.id=specification.product_id')
            ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $wholesalerModel = new \backend\models\Wholesaler();
            foreach ($data as $key=>$val) {
                $wholesalerInfo = $wholesalerModel::findOne($val['wholesaler_id']);
                $data[$key]['wholesaler_name'] = $wholesalerInfo->name;
                $data[$key]['price'] = sprintf("%.2f", $val['price'] /100);

                 //中文不转码
                $data[$key]['item_detail'] = json_encode(json_decode($val['item_detail'],true),JSON_UNESCAPED_UNICODE);
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }


    /**
     * ajax获取商品
     * @return array
     */
    public function actionGetgoodlist()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Product();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $name =  isset($keys['name'])? $keys['name'] : '';

        $where = [
            'status'=>1,
            'del'=>1
        ];
        $andWhere = [];

        $name = trim($name);
        if ($name) {
            $andWhere = [
                'like', 'name', $name
            ];
        }

        $third_category_id =  isset($keys['third_category_id'])?  intval($keys['third_category_id']) : 0;
        $wholesaler_id =  isset($keys['wholesaler_id'])? intval($keys['wholesaler_id']) : 0;

        if ($third_category_id) {
            $where['third_category_id'] = $third_category_id;
        }

        if ($wholesaler_id) {
            $where['wholesaler_id'] = $wholesaler_id;
        }

        $conut = $model::find()->where($where)->where($andWhere)->count();

        $offset = $limit * ($page - 1);
        $select = 'id,name,wholesaler_id';
        $data = $model::find()->where($where)->andWhere($andWhere)->select($select)->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $wholesalerModel = new \backend\models\Wholesaler();
            foreach ($data as $key=>$val) {
                $wholesalerInfo = $wholesalerModel::findOne($val['wholesaler_id']);
                $data[$key]['wholesaler_name'] = $wholesalerInfo->name;
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 选择店铺自提点
     */
    public function actionStore()
    {
        $this->layout = false;
        $data = [];

         //获取省份
         $province = $this->getRegionData(1);
         $data['province'] = $province;
          return $this->render('store', $data);
    }

    /**
     * ajax获取店铺列表
     * @return array
     */
    public function actionGetstorelist()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Store();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $name =  isset($keys['name'])? $keys['name'] : '';
        $province =  isset($keys['province'])? intval($keys['province']) : 0;
        $city     =  isset($keys['city'])? intval($keys['city']) : 0;
        $district =  isset($keys['district'])? intval($keys['district']) : 0;
        $owner_user_name = isset($keys['owner_user_name'])? $keys['owner_user_name'] : '';
        $store_phone = isset($keys['store_phone'])? $keys['store_phone'] : '';


        $name = trim($name);
        $owner_user_name = trim($owner_user_name);
        $store_phone = trim($store_phone);

        $andWhere1 = [];
        if ($name) {
            $andWhere1 = [
                'like', 'store.name', $name
            ];
        }

        $andWhere2 = [];
        if ($owner_user_name) {
            $andWhere2 = [
                'like', 'store.owner_user_name', $owner_user_name
            ];
        }

        $andWhere3 = [];
        if ($store_phone) {
            $andWhere3 = [
                'like', 'store.store_phone', $store_phone
            ];
        }

        $third_category_id =  isset($keys['third_category_id'])?  intval($keys['third_category_id']) : 0;
        $wholesaler_id =  isset($keys['wholesaler_id'])? intval($keys['wholesaler_id']) : 0;

        $where = [];

        if ($province) {
            $where['store.province'] = $province;
        }

        if ($city) {
            $where['store.city'] = $city;
        }

        if ($district) {
            $where['store.district'] = $district;
        }

        if ($wholesaler_id) {
            $where['product.wholesaler_id'] = $wholesaler_id;
        }

        $conut = $model::find()->where($where)->andWhere($andWhere1)->andWhere($andWhere2) ->andWhere($andWhere3)->count();

        $offset = $limit * ($page - 1);
        $select = 'store.id,store.store_phone,store.name,store.owner_user_name,store.address,region.name as city_name';
        $data = $model::find()->where($where)->andWhere($andWhere1)->andWhere($andWhere2) ->andWhere($andWhere3)->select($select)
            ->leftJoin('region','region.code=store.city')
            ->limit($limit)->offset($offset)->asArray()->all();

        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * ajax获取地区列表
     * @return array
     */
    public function actionGetregionlist()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $code = isset($get['code'])? intval($get['code']) : 0;

        $data = [];
        $model = new \backend\models\Region();

        $where = [
            'code'=>$code
        ];

        $info = $model::find()->where($where)->asArray()->one();

        if (empty($info)) {
            $res = ['code'=>0,'data'=>[]];
            return $res;
        }

        $where = [
            'parent_id'=>$info['id']
        ];

        $select = 'id,name,code';
        $data = $model::find()->where($where)->select($select)->asArray()->all();
        $res = ['code'=>0,'data'=>$data];
        return $res;
    }

    /**
     * 获取地区数据
     * @param int $level
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRegionData($level=1)
    {
        //获取省份
        $regionModel = new \backend\models\Region();
        $level = intval($level);
        $data = $regionModel::find()->where(['level'=>$level,'del'=>1])->asArray()->all();
        return $data;
    }

    /**
     * 选择专题
     * @return string
     */
    public function actionTopic()
    {
        $this->layout = false;
        $data = [];
        return $this->render('topic', $data);
    }

    /**
     * ajax获取专题列表
     * @return array
     */
    public function actionGettopics()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Topic();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $title =  isset($keys['title'])? $keys['title'] : '';
        $topicId =  isset($keys['topicId'])? intval($keys['topicId']) : 0;


        $title = trim($title);

        $where = [];
        if ($title) {
            $where = [
                'like', 'title', $title
            ];
        }

        $where2 = [
            'status'=>1
        ];
        if ($topicId) {
            $where2['id'] = $topicId;
        }

        $conut = $model::find()->where($where)->andWhere($where2)->count();

        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where)->andWhere($where2)->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 选择拼团活动的商品
     * @return string
     */
    public function actionPintuanproduct()
    {
        $this->layout = false;
        $data = [];
        return $this->render('pintuanproduct', $data);
    }

    /**
     * ajax获取拼团商品
     * @return array
     */
    public function actionGetpintuanproducts()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\PintuanActivity();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $productName =  isset($keys['productName'])? $keys['productName'] : '';
        $actId =  isset($keys['actId'])? intval($keys['actId']) : 0;
        $productName = trim($productName);

        $where = [
            'pintuan_activity.status'=>1,
            'pintuan_activity.del'=>1,
            'pintuan_activity_specification.del'=>1
        ];

        $where2 = [];
        if ($productName) {
            $where2 = [
                'like', 'product.name', $productName
            ];
        }

        if ($actId) {
            $where['pintuan_activity.id'] = $actId;
        }

        if ($actId) {
            $conut =$model::find()->join('LEFT JOIN','pintuan_activity_specification','pintuan_activity_specification.pintuan_activity_id=pintuan_activity.id')
                ->where($where)->count();
        } else if ($productName) {
            $conut =$model::find()->join('LEFT JOIN','pintuan_activity_specification','pintuan_activity_specification.pintuan_activity_id=pintuan_activity.id')
                                  ->join('LEFT JOIN','product','product.id=pintuan_activity.product_id')
                                  ->where($where)->andWhere($where2)->count();
        } else {
            $conut =$model::find()->join('LEFT JOIN','pintuan_activity_specification','pintuan_activity_specification.pintuan_activity_id=pintuan_activity.id')
                ->where($where)->count();
        }

        $offset = $limit * ($page - 1);
        $select = 'pintuan_activity.id,pintuan_activity.title,pintuan_activity.product_id,pintuan_activity_specification.specification_id,product.name as productName,specification.item_detail';
        $data = $model::find()->select($select)->where($where)->andWhere($where2)
            ->leftJoin('pintuan_activity_specification','pintuan_activity_specification.pintuan_activity_id=pintuan_activity.id')
            ->leftJoin('specification','specification.id=pintuan_activity_specification.specification_id')
            ->leftJoin('product','product.id=pintuan_activity.product_id')
            ->orderBy('pintuan_activity.id desc')->limit($limit)->offset($offset)->asArray()->all();
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 选择拼团活动
     * @return string
     */
    public function actionPintuan()
    {
        $this->layout = false;
        $data = [];
        return $this->render('pintuan', $data);
    }

    /**
     * ajax获取拼团
     * @return array
     */
    public function actionGetpintuans()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\PintuanActivity();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $productName =  isset($keys['productName'])? $keys['productName'] : '';
        $actId =  isset($keys['actId'])? intval($keys['actId']) : 0;
        $productName = trim($productName);

        $where = [
            'pintuan_activity.status'=>1,
            'pintuan_activity.del'=>1
        ];

        $where2 = [];
        if ($productName) {
            $where2 = [
                'like', 'product.name', $productName
            ];
        }

        if ($actId) {
            $where['pintuan_activity.id'] = $actId;
        }

        if ($actId) {
            $conut =$model::find()->where($where)->count();
        } else if ($productName) {
            $conut =$model::find()->join('LEFT JOIN','product','product.id=pintuan_activity.product_id')
                                  ->where($where)->andWhere($where2)->count();
        } else {
            $conut =$model::find()->where($where)->count();
        }

        $offset = $limit * ($page - 1);
        $select = 'pintuan_activity.*,product.name as productName';
        $data = $model::find()->select($select)->where($where)->andWhere($where2)
                              ->leftJoin('product','product.id=pintuan_activity.product_id')
                              ->orderBy('pintuan_activity.id desc')->limit($limit)->offset($offset)->asArray()->all();

        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }
}
