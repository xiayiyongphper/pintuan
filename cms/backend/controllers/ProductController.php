<?php

namespace backend\controllers;

use backend\models\Specification;
use backend\models\SpecificationItem;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\tools\Excel;
use backend\models\Category;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    private $putOnOut = array(1,2); // 商品上下架状态，1和2
    private $third_platform_arr = array("taobao.com"=>2,"jd.com"=>3,"tmall.com"=>4); // 第三方平台对应的顶级域名
    const IMAGES_COMBINE_SIGN = ";"; // 图片连接符
    const WHOLESALER_IDS_SIGN = ","; // 导入excel供货商的分割符

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $specificationList = Specification::find()->where([
            'product_id' => $id
        ])->asArray()->all();

        $info = $this->findModel($id);

        //获取三级分类
        $category_model = new \backend\models\Category();
        $info->third_category_id = $category_model->getCategoryNames($info->third_category_id);

        //获取供应商
        $wholesaler_model = new \backend\models\Wholesaler();
        $wholesaler_info = $wholesaler_model::findOne($info->wholesaler_id);
        $info->wholesaler_id = $wholesaler_info->name;
        unset($wholesaler_info);

        //获取状态 状态：1-上架,2-下架
        if ( $info->status == 1 ) {
            $info->status = '上架';
        } else {
            $info->status = '下架';
        }

        //是否删除：1-正常，2-删除
        if ( $info->del == 1 ) {
            $info->del = '正常';
        } else {
            $info->del = '删除';
        }

        return $this->render('view', [
            'model' => $info,
            'specificationList' => $specificationList
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $data = Yii::$app->request->post();

        if(! $model->load($data)){
            $p1 = $p2 = [];
            $p3 = $p4 = [];
            return $this->render('create', [
                'p1' => $p1,
                'p2' => $p2,
                'p3' => $p3,
                'p4' => $p4,
                'model' => $model,
            ]);
        }

        $product = $data['Product'];
        //验证图片是否上传
        if (!isset($product['images']) || empty($product['images'])) {
            return $this->throwException("请上传商品图片！");
        }

        if (!isset($product['description']) || empty($product['description'])) {
            return $this->throwException("请上传商品详情图片！");
        }

        //商品分类
        if (!isset($product['third_category_id']) || empty($product['third_category_id'])) {
            return $this->throwException("请选择商品分类！");
        }

        //验证商品规格数据是否合理
        $check = $this->_validateSpecification($data);
        if (false == $check) {
            return $this->throwException("商品规格数据有重复或者不合法，请认真检查");
        }

//        Tools::log($model->name,'pro.log');
        $attrMap = [
            'name' => '商品名称',
            'wholesaler_id' => '供应商',
            'third_category_id' => '三级分类',
        ];

        foreach ($attrMap as $k => $v){
            if(empty($model->$k)){
                return $this->throwException($v."不能为空");
            }
        }
        //$model->description = $model->description ? : '';
        $model->status = $model->status ? : 1;
        $model->sort = $model->sort ? : 0;
        //生成智能销售数据
        $model->fake_sold_base = rand(1000, 10000);

        if(!$model->save()){
            Tools::logException(new Exception(json_encode($model->errors)));
//            throw new \RuntimeException('新增失败');
            return $this->throwException("新增失败");
        }

        //规格
        if(isset($data['list']) && isset($data['columns']) && isset($data['data'])){
            $this->saveSpecification($model->id,json_decode($data['list'],true),json_decode($data['columns'],true),json_decode($data['data'],true));
        }

        //发送mq
        $mqData = [
            'route' => 'taskProduct.productCreateProcess',
            'params' => [
                'product_id' => [
                    $model->id
                ]
            ]
        ];
        $mq =  Yii::$app->get('RabbitMQ');
        $mq->publish($mqData);

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
//        Tools::log($data,'pro.log');
        if(! $model->load($data)){
            $images = array_filter(explode(';', $model->images));
            $p1 = $p2 = [];
            if ($images) {
                foreach ($images as $k => $v) {
                    $p1[$k] = $v;
                    $p2[$k] = [
                        'url' => \yii\helpers\Url::to(['/product/image-delete']),
                        'key' => $v,
                    ];
                }
            }

            //商品详情
            $description_images = array_filter(explode(';', $model->description));
            $p3 = $p4 = [];
            if ($description_images) {
                foreach ($description_images as $index => $desc) {
                    $p3[$index] = $desc;
                    $p4[$index] = [
                        'url' => \yii\helpers\Url::to(['/product/image-delete']),
                        'key' => $desc,
                    ];
                }
            }

            //获取商品分类
            $category_model = new \backend\models\Category();
            $category = $category_model::findOne($model->third_category_id);
            $cids = explode('/', $category->path);
            unset($category);

            return $this->render('update', [
                'p1' => $p1,
                'p2' => $p2,
                'p3' => $p3,
                'p4' => $p4,
                'model' => $model,
                'parent_id' => $cids[1],
                'sencond_id' => $cids[2]
            ]);
        }

        $product = $data['Product'];
        //验证图片是否上传
        if (!isset($product['images']) || empty($product['images'])) {
            throw new NotFoundHttpException("请上传商品图片！");
        }
        if (!isset($product['description']) || empty($product['description'])) {
            throw new NotFoundHttpException("请上传商品详情图片！");
        }

        //商品分类
        if (!isset($product['third_category_id']) || empty($product['third_category_id'])) {
            throw new NotFoundHttpException("请选择商品分类！");
        }

//        Tools::log($model->name,'pro.log');
        $attrMap = [
            'name' => '商品名称',
            'wholesaler_id' => '供应商',
            'third_category_id' => '三级分类',
        ];

        foreach ($attrMap as $k => $v){
            if(empty($model->$k)){
                throw new NotFoundHttpException($v."不能为空");
            }
        }

        $model->description = $model->description ? : '';
        $model->status = $model->status ? : 1;
        $model->sort = $model->sort ? : 0;

        //下架时 判断是否有相关的数据存在
        if ($model->status == 2) {
            //验证是否有拼团活动存在
            $pinModel = new \backend\models\PintuanActivity();
            $curDate = date('Y-m-d H:i:s', time());
            $timeWhere = [
                'and',
                ['<=', 'start_time', $curDate],
                ['>=', 'end_time', $curDate]
            ];
            $pinWhere = ['product_id'=>$id,'status'=>1,'del'=>1];
            $info = $pinModel::find()->where($pinWhere)->andWhere($timeWhere)->asArray()->one();
            if ($info) {
                throw new NotFoundHttpException("该商品存在拼团活动，不能下架！");
            }
        }

        if(!$model->save()){
            Tools::logException(new Exception(json_encode($model->errors)));
//            throw new \RuntimeException('更新失败');
            throw new NotFoundHttpException("更新失败");
        }


//        if(isset($data['list']) && isset($data['columns']) && isset($data['data'])){
//            $this->saveSpecification($model->id,json_decode($data['list'],true),json_decode($data['columns'],true),json_decode($data['data'],true));
//        }

        //发送mq
        $mqData = [
            'route' => 'taskProduct.productUpdateProcess',
            'params' => [
                'product_id' => [
                    $model->id
                ]
            ]
        ];
        $mq =  Yii::$app->get('RabbitMQ');
        $mq->publish($mqData);

        $this->redirect(['view', 'id' => $model->id]);
    }

    public function saveSpecification($productId,$list,$columns,$data){
        $existSpeItemMap = [];
        $speItems = SpecificationItem::findAll(['product_id' => $productId]);
        if($speItems){
            foreach ($speItems as $model){
                $existSpeItemMap[$model->specification_name."-".$model->specification_value] = $model;
            }
        }

        Tools::log($list,'pro.log');
        if(!empty($list)){
            foreach ($list as $item){
                Tools::log($item,'pro.log');
                Tools::log($item['v'],'pro.log');
                if(empty($item['v'])) continue;

                foreach ($item['v'] as $v){
                    //已经存在
                    if(isset($existSpeItemMap[$item['k']."-".$v])){
                        unset($existSpeItemMap[$item['k']."-".$v]);
                        continue;
                    }

                    $specificationItem = new SpecificationItem();
                    $specificationItem->product_id = $productId;
                    $specificationItem->specification_name = $item['k'];
                    $specificationItem->specification_value = $v;
                    Tools::log($specificationItem,'pro.log');

                    if(!$specificationItem->save()){
                        Tools::logException(new Exception(json_encode($specificationItem->errors)));
                    }
                }
            }
        }

        //删除剩下的
        if(!empty($existSpeItemMap)){
            /** @var SpecificationItem $model */
            foreach ($existSpeItemMap as $model){
                $model->delete();
            }
        }

        //处理规格
        if(empty($data) || empty($columns)) return;

        $columnMap = [];
        foreach ($columns as $column){
            $columnMap[$column['k']] = $column['v'];
        }

        //商品工具
        $goods_tool = new \common\tools\Goods();

        foreach ($data as $spe){
            if(empty($spe)) continue;

            $itemDetail = [];
            foreach ($spe as $k => $v){
                if(isset($columnMap[$k])){
                    $itemDetail[$columnMap[$k]] = $v;
                    unset($spe[$k]);
                }
            }
            $spe['item_detail'] = json_encode($itemDetail,JSON_UNESCAPED_UNICODE);

            $speModel = new Specification();
            $speModel->product_id = $productId;
            $speModel->item_detail = $spe['item_detail'];
//            $speModel->item_ids = '';
            $speModel->purchase_price = isset($spe['purchase_price']) ? $spe['purchase_price'] * 100 : 0;
            $speModel->pick_commission = isset($spe['pick_commission']) ? $spe['pick_commission'] * 100 : 0;
            $speModel->promote_commission = isset($spe['promote_commission']) ? $spe['promote_commission'] * 100 : 0;
            $speModel->price = isset($spe['price']) ? $spe['price'] * 100 : 0;
            $speModel->qty = isset($spe['qty']) ? $spe['qty'] : 0;

            //规格编码
            $speModel->barcode = $goods_tool::createBarcode();

            Tools::log($speModel,'pro.log');
            if(!$speModel->save()){
                Tools::logException(new Exception(json_encode($speModel->errors)));
            }
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = intval($id);
        $model = $this->findModel($id);
        if($model){
            //验证是否有拼团活动存在
            $pinModel = new \backend\models\PintuanActivity();
            $curDate = date('Y-m-d H:i:s', time());
            $timeWhere = [
                'and',
                ['<=', 'start_time', $curDate],
                ['>=', 'end_time', $curDate]
            ];
            $pinWhere = ['product_id'=>$id,'status'=>1,'del'=>1];
            $info = $pinModel::find()->where($pinWhere)->andWhere($timeWhere)->asArray()->one();
            if ($info) {
                throw new NotFoundHttpException('该商品存在拼团活动，不能删除！', 0000);
            }

            $model->del = 2;
            if(! $model->save()){
                Tools::logException(new Exception(json_encode($model->errors)));
                return $this->NotFoundHttpException("删除失败");
            }

            //删除商品规格
            $specification_model = new \backend\models\Specification();
            $delData = ['del'=>2];
            $where = ['product_id'=>$id];
            $specification_model::updateAll($delData, $where);

            $specification_item_model = new \backend\models\SpecificationItem();
            $specification_item_model::updateAll($delData, $where);

            //发送mq
            $mqData = [
                'route' => 'taskProduct.productDeleteProcess',
                'params' => [
                    'product_id' => [
                        $id
                    ]
                ]
            ];
            $mq =  Yii::$app->get('RabbitMQ');
            $mq->publish($mqData);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionImageUpload()
    {
        try {
            $model = new Product();
            $imageFile = UploadedFile::getInstance($model, 'image');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'product', true);
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                throw new \Exception($result['msg'], $result['code']);
            }
            return json_encode([
                'files' => [
                    [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        'url' => $result['url'],
                        'thumbnailUrl' => str_replace('600x600', '180x180', $result['url']),
                        'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Tools::logException($e);
        } catch (\Error $e) {

        }
        return '';
    }

    /**
     * 不走切图
     * @return string
     */
    public function actionImageUpload2()
    {
        try {
            $model = new Product();
            $imageFile = UploadedFile::getInstance($model, 'image');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'merchant', true);
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                throw new \Exception($result['msg'], $result['code']);
            }
            return json_encode([
                'files' => [
                    [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        'url' => $result['url'],
                        'thumbnailUrl' => str_replace('600x600', '180x180', $result['url']),
                        'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Tools::logException($e);
        } catch (\Error $e) {

        }
        return '';
    }


    public function actionImageDelete()
    {
        if ($id = \Yii::$app->request->post('key')) {
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }

    public function throwException($msg,$code = 100){
        return json_encode([
            'code' => $code,
            'msg' => $msg
        ]);
    }

    /**
     *
     * SPU导入、SPU导出、SKU导出、SKU导入
     * @throws \Exception
     */
    public function actionImport()
    {
        $post = yii::$app->request->post();

        if (isset($post['spu_in_btn'])) {
            //SPU导入
            $this->_importSpus('spu_in');
        } else if (isset($post['sku_in_btn'])) {
            //SKU导入
            $this->_importSkus('sku_in');
        } else if (isset($post['spu_out_btn'])){
            //SPU导出
            $this->_exportSpus($post);
        } else if (isset($post['sku_out_btn'])){
            //SkU导出
            $this->_exportSkus($post);
        }else if (isset($post['third_spu_in_btn'])){
            //第三方平台的商品导入
            $this->_importThirdSpus('third_spu_in');
        }
    }

    public function actionChoose()
    {
        $request = Yii::$app->request;

        if ($request->isAjax && $request->isPost) {
            $searchModel = new ProductSearch();
            $params['kw'] = $request->post('kw');
            $p = $request->post('p', 1);
            $pn = $request->post('pn', 15);

            $data = $searchModel->search2($params, $p, $pn);

            exit(json_encode($data));
        }

        $this->layout = false;
        return $this->render('choose');
    }

    public function actionChoosegood()
    {
        $request = Yii::$app->request;

        if ($request->isAjax && $request->isPost) {
            $searchModel = new ProductSearch();
            $params['kw'] = $request->post('kw');
            $p = $request->post('p', 1);
            $pn = $request->post('pn', 15);

            $data = $searchModel->search3($params, $p, $pn);

            exit(json_encode($data));
        }

        $this->layout = false;
        return $this->render('choose');
    }

    /**
     * 更新商品规格数据
     */
    public function actionUpdatespe()
    {
        $post = yii::$app->request->post();
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

        $ids_arr             = isset($post['ids'])? $post['ids'] : [];
        $purchase_price_arr  = isset($post['purchase_price'])? $post['purchase_price'] : [];
        $pick_commission_arr = isset($post['pick_commission'])? $post['pick_commission'] : [];
        $price_arr           = isset($post['price'])? $post['price'] : [];
        $qty_arr             = isset($post['qty'])? $post['qty'] : [];
        $img_arr             = isset($post['image'])? $post['image'] : [];

        if (empty($ids_arr) || empty($purchase_price_arr) || empty($pick_commission_arr) || empty($price_arr) || empty($qty_arr)) {
            return ['code'=>1,'message'=>'提交的数据错误'];
        }

        $model = new \backend\models\Specification();

        //商品id
        $productId = 0;
        $data = [];
        foreach ($ids_arr as $key=>$id) {
            if (!is_numeric($id) || $id<=0) {
                return ['code'=>1,'message'=>'提交的数据错误'];
            }

            $specification = $model::findOne($id);
            $data[$id] = $specification;

            if (empty($productId)) {
                $productId = $specification->product_id;
            }

            if (!$specification) {
                return ['code'=>1,'message'=>'提交的数据错误'];
            }

            if (!isset($purchase_price_arr[$key]) || !is_numeric($purchase_price_arr[$key]) || $purchase_price_arr[$key]<=0 || intval($purchase_price_arr[$key] * 100) <=0) {
                return ['code'=>1,'message'=>'提交的进价数据错误'];
            }

            if (!isset($pick_commission_arr[$key]) || !is_numeric($pick_commission_arr[$key]) || $pick_commission_arr[$key]<0 || intval($pick_commission_arr[$key] * 100) <0) {
                return ['code'=>1,'message'=>'提交的自提佣金数据错误'];
            }

            if (!isset($price_arr[$key]) || !is_numeric($price_arr[$key]) || $price_arr[$key]<=0 || intval($price_arr[$key] * 100) <=0) {
                return ['code'=>1,'message'=>'提交的售价数据错误'];
            }

            if (!isset($qty_arr[$key]) || !is_numeric($qty_arr[$key])) {
                return ['code'=>1,'message'=>'提交的库存数据错误'];
            }
        }

        foreach ($ids_arr as $key=>$id) {
            $specData = $data[$id];
            $purchase_price  = $purchase_price_arr[$key];
            $pick_commission = $pick_commission_arr[$key];
            $price           = $price_arr[$key];
            $qty             = $qty_arr[$key];
            $img             = $img_arr[$key];

            $specData->purchase_price  = intval($purchase_price *100);
            $specData->pick_commission = intval($pick_commission *100);
            $specData->price            = intval($price *100);
            $specData->qty              = $qty;
            $specData->image            = $img;
            $specData->save();
        }

        //发送mq
        $mqData = [
            'route' => 'taskProduct.productUpdateProcess',
            'params' => [
                'product_id' => [
                    $productId
                ]
            ]
        ];
        $mq =  Yii::$app->get('RabbitMQ');
        $mq->publish($mqData);

        return ['code'=>0,'message'=>'更新成功'];
    }

    /**
     * 商品规格上下架
     */
    public function actionUpdatespedel()
    {
        $post = yii::$app->request->post();
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        $id  = isset($post['id'])? $post['id'] : 0;
        $del = isset($post['del'])? $post['del'] : 0;

        if (empty($id) || empty($del) || !is_numeric($id) || !is_numeric($del) || $id<=0 || $del<=0) {
            return ['code'=>1,'message'=>'提交的数据错误'];
        }

        if (!in_array($del, [1,2])) {
            return ['code'=>1,'message'=>'提交的数据错误'];
        }

        $model         = new \backend\models\Specification();
        $specification = $model::findOne($id);

        if (!$specification) {
            return ['code'=>1,'message'=>'提交的数据错误'];
        }

        if ($del == $specification->del) {
            return ['code'=>1,'message'=>'不能重复操作！'];
        }

        $data = ['del'=>$del];
        $where = ['id'=>$id];
        $res = $model::updateAll($data, $where);

        if (!$res) {
            return ['code'=>1,'message'=>'操作失败，请稍后再尝试！'];
        }

        //发送mq
        $mqData = [
            'route' => 'taskProduct.productUpdateProcess',
            'params' => [
                'product_id' => [
                    $specification->product_id
                ]
            ]
        ];
        $mq =  Yii::$app->get('RabbitMQ');
        $mq->publish($mqData);

        return ['code'=>0,'message'=>'更新成功'];
    }

    /**
     * SPU导入修改：上下架
     * @param $fileName
     */
    private function _importSpus($fileName)
    {
        $model = new \backend\models\Product();
        $statusLArr = [
            '上架',
            '下架'
        ];
        $sheetData = $this->_getExcelData($fileName);

        if (empty($sheetData)) {
            echo '请导入合法的数据文件';
            exit;
        }

        unset($sheetData[0]);
        if (empty($sheetData)) {
            echo '请导入合法的数据文件';
            exit;
        }

        foreach ($sheetData as $row=>$product) {
            $row = $row + 1;

            if (empty($product[0]) || empty($product[4])) {
                echo "第{$row}行的SPU编码或者上下架状态异常<br/>";
                continue;
            }

            if (!is_numeric($product[0])) {
                echo "第{$row}行的SPU编码异常<br/>";
                continue;
            }

            if ($product[0] <=0 ) {
                echo "第{$row}行的SPU编码异常<br/>";
                continue;
            }

            if (!in_array($product[4], $statusLArr)) {
                echo "第{$row}行的上下架状态异常<br/>";
                continue;
            }

            if ($product[4] == '上架') {
                $status = 1;
            } else {
                $status = 2;
            }

            $id = intval($product[0]);
            $info = $model::findOne($id);

            if (empty($info)) {
                echo "第{$row}行的商品不存在【SPU编码：{$id}】<br/>";
                continue;
            }

            //修改上下架
            $info->status = $status;
            $info->update_at = date('Y-m-d H:i:s', time());
            $res = $info->save();
            if (!$res) {
                echo "第{$row}行的更新失败【SPU编码：{$id}】<br/>";
                continue;
            }
        }
        echo 'SPU导入修改完成';
        exit;
    }


    /**
     * Sku导入修改:进货价、自提佣金、销售价、库存
     * @param $fileName
     */
    private function _importSkus($fileName)
    {
        $model = new \backend\models\Specification();
        $sheetData = $this->_getExcelData($fileName);

        if (empty($sheetData)) {
            echo '请导入合法的数据文件';
            exit;
        }

        unset($sheetData[0]);

        if (empty($sheetData)) {
            echo '请导入合法的数据文件';
            exit;
        }

        //6进货价7自提佣金8销售价9库存
        foreach ($sheetData as $row=>$specification) {
            $row = $row + 1;

            if (empty($specification[0]) || empty($specification[1])) {
                echo "第{$row}行的SKU编码或者商品名称异常<br/>";
                continue;
            }

            if (!is_numeric($specification[0])) {
                echo "第{$row}行的SKU编码异常<br/>";
                continue;
            }

            if ($specification[0] <=0 ) {
                echo "第{$row}行的SKU编码异常<br/>";
                continue;
            }

            $purchase_price = 0;
            if ($specification[6] && (!is_numeric($specification[6]) || $specification[6]<=0)) {
                echo "第{$row}行的进货价异常<br/>";
                continue;
            } else {
                $purchase_price = intval($specification[6] * 100);
                if ($purchase_price <=0) {
                    echo "第{$row}行的进货价异常<br/>";
                    continue;
                }
            }

            $pick_commission = 0;
            if ($specification[7] && (!is_numeric($specification[7]) || $specification[7]<=0)) {
                echo "第{$row}行的自提佣金异常<br/>";
                continue;
            } else {
                $pick_commission = intval($specification[7] * 100);
                if ($pick_commission <=0) {
                    echo "第{$row}行的自提佣金异常<br/>";
                    continue;
                }
            }

            $price = 0;
            if ($specification[8] && (!is_numeric($specification[8]) || $specification[8]<=0)) {
                echo "第{$row}行的销售价异常<br/>";
                continue;
            } else {
                $price = intval($specification[8] * 100);
                if ($price <=0) {
                    echo "第{$row}行的销售价异常<br/>";
                    continue;
                }
            }

            $qty = 0;
            if ($specification[9] && (!is_numeric($specification[9]) || $specification[9]<=0)) {
                echo "第{$row}行的库存异常<br/>";
                continue;
            } else {
                $qty = intval($specification[9]);
            }

            $id     = intval($specification[0]);
            $info = $model::findOne($id);

            if (empty($info)) {
                echo "第{$row}行的商品异常<br/>";
                continue;
            }

            //修改 进货价、自提佣金、销售价、库存
            $info->purchase_price = $purchase_price;
            $info->pick_commission = $pick_commission;
            $info->price = $price;

            if ($qty) {
                $info->qty = $qty;
            }

            $res = $info->save();
            if (!$res) {
                echo "第{$row}行的更新失败<br/>";
                continue;
            }
        }

        echo 'Sku导入修改完成';
        exit;
    }

    /**
     * SPU导出
     */
    private function _exportSpus($post)
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);

        $res = $this->_handleExportWHere($post);

        if (!$res) {
            echo '请填写查询条件，然后才可以导出！';
            exit;
        }

        $where = [];
        $filterWhere = [];

        if (isset($res['id'])) {
            $where['id'] =  $res['id'];
        } else {
            if (isset($res['name'])) {
                $filterWhere = ['like', 'name', $res['name']];
                unset($res['name']);
            }
            $where = $res;
            $where['del'] = 1;
        }

        $model = new \backend\models\Product();
        $data = $model::find()->where($where)->andFilterWhere($filterWhere)->asArray()->all();

        if ($data) {
            $wholesaler_model = new \backend\models\Wholesaler();
            $category_model = new \backend\models\Category();
            $res = [];
            foreach ($data as $key=>$val) {
                //商品分类
                $categoryNames = $category_model->getCategoryNames($val['third_category_id']);
                //供货商
                $wholesaler = $wholesaler_model::findOne($val['wholesaler_id']);
                $delMsg = '上架';
                if ($val['status'] == 2) {
                    $delMsg = '下架';
                }
                $res[] = [
                    $val['id'],
                    $val['name'],
                    $categoryNames,
                    $wholesaler->name,
                    $delMsg
                ];
                unset($data[$key]);
            }
            $title = ['SPU编码','商品名称','商品分类','供货商','上下架'];
            $fileName = 'spu-export-'. date('YmdHis', time());
            Excel::exportExcel($title, $res, $fileName);
            exit;
        }
    }


    /**
     * SKU导出
     */
    private function _exportSkus($post)
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);

        $res = $this->_handleExportWHere($post);

        if (!$res) {
            echo '请填写查询条件，然后才可以导出！';
            exit;
        }

        $model = new \backend\models\Specification();
        $where = [];
        $filterWhere = [];

        if (isset($res['id'])) {
            $where['product_id'] =  $res['id'];
            $where['del'] =  1;
            $data = $model::find()->where($where)->asArray()->all();
        } else {
            if (isset($res['name'])) {
                $filterWhere = ['like', 'product.name', $res['name']];
                unset($res['name']);
            }

            if (isset($res['third_category_id'])) {
                $where['product.third_category_id'] =  $res['third_category_id'];
            }

            if (isset($res['wholesaler_id'])) {
                $where['product.wholesaler_id'] =  $res['wholesaler_id'];
            }

            if (isset($res['status'])) {
                $where['product.status'] =  $res['status'];
            }

            $where['product.del'] =  1;

            $data = $model::find()->leftJoin('product', 'product.id=specification.product_id')->where($where)->andFilterWhere($filterWhere)->asArray()->all();
        }

        if ($data) {
            $product_model = new \backend\models\Product();
            $wholesaler_model = new \backend\models\Wholesaler();
            $category_model = new \backend\models\Category();
            $res = [];
            foreach ($data as $key=>$val) {
                //获取商品
                $product = $product_model::findOne($val['product_id']);
                //商品分类
                $categoryNames = $category_model->getCategoryNames($product->third_category_id);
                //供货商
                $wholesaler = $wholesaler_model::findOne($product->wholesaler_id);

                $delMsg = '上架';
                if ($product->status == 2) {
                    $delMsg = '下架';
                }

                //获取规格
                $item_detail = json_decode($val['item_detail'], true);
                $item_detail_str = '';
                foreach ($item_detail as $item) {
                    $item_detail_str .= $item . ';';
                }
                $item_detail_str = trim($item_detail_str, ';');
                $res[] = [
                    $val['id'],
                    $product->name,
                    $categoryNames,
                    $wholesaler->name,
                    $delMsg,
                    $item_detail_str,
                    sprintf('%.2f', $val['purchase_price']/100),
                    sprintf('%.2f', $val['pick_commission']/100),
                    sprintf('%.2f', $val['price']/100),
                    $val['qty']
                ];
                unset($data[$key]);
            }

            $title = ['SKU编码','商品名称','商品分类','供货商','上下架', '规格', '进货价(单位：元)', '自提佣金(单位：元)', '销售价(单位：元)', '库存'];
            $fileName = 'sku-export-'. date('YmdHis', time());
            Excel::exportExcel($title, $res, $fileName);
            exit;
        }
    }

    /**
     * 获取上传excel文件的数据
     * @param $fileName file的html控件名称
     */
    private function _getExcelData($fileName)
    {
        $tmp = UploadedFile::getInstanceByName($fileName);
        if (empty($tmp) || empty($tmp->tempName)) {
            return false;
        }
        $file_name = $tmp->tempName;
        return Excel::readExcelSheet($file_name);
    }

    /**
     * 验证商品属性数据
     * @param $data
     */
    private function _validateSpecification($data)
    {
        $list     = isset($data['list'])? json_decode($data['list'], true) : [];
        $dataList = isset($data['data'])? json_decode($data['data'], true) : [];

        //异常处理
        if (empty($list) || empty($dataList)) {
            return false;
        }
        //验证规格是否重复、空
        $specifications = [];
        $spec_val       = [];
        foreach ($list as $key=>$val) {
            if (empty($val['k'])) {
                //规格为空
                return false;
            }

            $spec_val = [];
            if (in_array($val['k'], $specifications)) {
                //规格重复
                return false;
            }
            $specifications[] =  $val['k'];

            //判断规格值是否重复、空
            $valueArr = $val['v'];
            foreach ($valueArr as $d) {
                if (empty($d)) {
                    //属性值为空
                    return false;
                }
                if (in_array($d, $spec_val)) {
                    //属性值重复
                    return false;
                }
                $spec_val[] =  $d;
            }
        }

        //验证填写的数据是否合理
        foreach ($dataList as $rs) {
            if (!isset($rs['purchase_price']) || !isset($rs['pick_commission']) || !isset($rs['promote_commission'])
                || !isset($rs['price']) || !isset($rs['qty'])
            ) {
                //必填项为空
                return false;
            }
            if (!is_numeric($rs['purchase_price']) || $rs['purchase_price']<=0) {
                return false;
            }

            if (!is_numeric($rs['pick_commission']) || $rs['pick_commission']<0) {
                return false;
            }

            if (!is_numeric($rs['promote_commission']) || $rs['promote_commission']<0) {
                return false;
            }

            if (!is_numeric($rs['price']) || $rs['price']<=0) {
                return false;
            }

            if (!is_numeric($rs['qty']) || $rs['qty']<0) {
                return false;
            }
        }
        return true;
    }

    /**
     * 第三方平台的商品导入
     * @param $fileName  ('third_spu_in')
     */
    private function _importThirdSpus($fileName)
    {
        ini_set('memory_limit','521M');
        set_time_limit(0);

        $sheetData = $this->_getExcelData($fileName);
        if (empty($sheetData)) {
            echo '请导入合法的数据文件';
            exit;
        }
        $row = $success=0;
        $sheetDatas = $product_ids_arr = []; // 去掉了空数组后的
        foreach($sheetData as $val){
            if(!Tools::is_array_null($val)){ // 判断数组是否为空
                $sheetDatas[] = $val;
            }
        }
        if (empty($sheetDatas)) {
            echo '请导入合法的数据文件';
            exit;
        }

        foreach ($sheetDatas as $product) {
            if($product[0] == '供货商id'){ // 跳过第一行
                continue;
            }
            $row++;
            // 前三列不能为空
            if (empty($product[0]) || empty($product[1]) || empty($product[2]) || empty($product[5]) || empty($product[8])) {
                echo "第{$row}行的供货商id、上下架、商品名称、商品现价、商品链接url中有空值<br/>";
                continue;
            }
            // 供应商，可能会有多个，用/ 分开
            if (!is_numeric($product[0]) && strpos($product[0],self::WHOLESALER_IDS_SIGN) === false) {
                echo "第{$row}行的供货商id不对--{$product[0]}<br/>";
                continue;
            }

            if (!is_numeric($product[1]) || !in_array($product[1],$this->putOnOut)) {
                echo "第{$row}行的上下架数值不对<br/>";
                continue;
            }
            // 默认值
            $date = date("Y-m-d H:i:s");

            // 分类的处理
            if(!Tools::is_not_json($product[4])){ // 是json 格式 ToDo
                $cates = json_decode($product[4],true);
                $categories[1] = $cates[0];
                $categories[2] = $cates[1];
                $categories[3] = $cates[2];
                // 判断一二三级类目是否找到，找到第三级类目的id,找不到false
                $cateExist = Category::getCateExist($categories);
                if($cateExist !== false){ // 找到了
                    $third_category_id = $cateExist;
                }else{
                    echo "第{$row}行的类目查不到<br/>";
                    continue;
                }
            }else if(is_numeric($product[4])){ // 是数字
                // 先不处理
                echo "第{$row}行的类目查不到<br/>";
                continue;
            }

            // 所有的规格都用这个价格
            if(!is_numeric($product[5])){
                echo "第{$row}行的商品现价格式不对<br/>";
                continue;
            }

            if(strpos($product[5],"-") !== false){ // 含- 的话，sku 的价格为 product[6] 里面 sku_price 先定直接报错
                // 先不处理
                echo "第{$row}行的商品现价有-符号，请处理<br/>";
                continue;
            }

            $price = 100*$product[5]; //商品价格

            // false 为不是url 地址
            if(filter_var($product[8], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false){
                echo "第{$row}行的商品链接url格式不对<br/>";
                continue;
            }

            $third_url = trim($product[8]);

            $third_platform = $this->getPlatformByUrl($product[8]);
            $third_goods_id = $this->getThirdGoodsId($third_platform,$third_url);

            if(empty($product[6]) || $product[6] == "[]" || Tools::is_not_json($product[6])){
                $skus_infos = [];
                $item_detail = [];
            }else{
                $skus_infos = json_decode($product[6],true);
                if(!is_array($skus_infos)){ // 不是数组
                    echo "第{$row}行的商品规格(sku)格式有误<br/>";
                    continue;
                }
                $item_detail = [];
                // 如果 sku 类型名称只有一种，
                if(count($skus_infos) == 1){
                    $label = $skus_infos[0]['label'];
                    foreach ($skus_infos[0]['values'] as $val){
                        $item_detail[][$label] = $val['desc'];
                    }
                }else{
                    $combineSets =  Tools::combineMulSkuItemdetails($skus_infos); // 拼成以下格式
                    /*array(
                        array('颜色--摩卡金|','颜色--亮黑色|','颜色--香槟金|'),
                        array('版本--标准版|','版本--套装版|'),
                        array('内存--64GB|','内存--128GB|')
                    );*/
                    $res = Tools::CartesianProduct($combineSets);
                    for($i=0;$i<count($res);$i++){
                        $speArrAll = explode('|',rtrim($res[$i],"|")); // 0=>颜色--摩卡金,1=>版本--标准版,2=>内存--64GB
                        foreach ($speArrAll as $val){
                            $speArr = explode('--',$val);
                            $item_detail[$i][$speArr[0]] = $speArr[1];
                        }
                    }
                }
            }

            /*
            // 规格详情没有做到
            $product_sku_detail = $product[7];
            if(empty($product[7]) || $product[7] == "[]"){
                // 规格详情为无
            }*/

            $images = ""; // 数据库中商品主图
            $imgMainArrs = []; // 商品主图
            if(!empty($product[9]) && !Tools::is_not_json($product[9]) && $product[9] != "[]" ){
                $image_urls = json_decode($product[9],true);
                foreach ($image_urls as $val){
                    $imgMainArrs[] = isset($val['image_url'])?$val['image_url']:"";
                }
                if(!empty($imgMainArrs)){
                    $resUploadFiles = Ftp::uploadMulFiles($imgMainArrs,"product");
                    $resUploadFiles = json_decode($resUploadFiles,true);
                    if($resUploadFiles['code'] == 0){
                        $images = implode(self::IMAGES_COMBINE_SIGN,$resUploadFiles['url']);
                    }
                    unset($resUploadFiles);
                }else{
                    echo "第{$row}行的商品没有商品主图，无法导入<br/>";
                    continue;
                }
            }else{
                echo "第{$row}行的商品没有商品主图，无法导入<br/>";
                continue;
            }


            $description = ""; // 数据库中商品详情
            $imgDescriptionArrs = []; // 商品详情中的图片集
            if(!empty($product[10])){
                $imgDescriptionArrs = Tools::getImgsArr($product[10],$third_platform);
                if(!empty($imgDescriptionArrs)){
                    $resUploadFiles = Ftp::uploadMulFiles($imgDescriptionArrs);
                    $resUploadFiles = json_decode($resUploadFiles,true);
                    if($resUploadFiles['code'] == 0){
                        $description = implode(self::IMAGES_COMBINE_SIGN,$resUploadFiles['url']);
                    }
                    unset($resUploadFiles);
                }else{
                    echo "第{$row}行的商品没有商品详情图，无法导入<br/>";
                    continue;
                }
            }else{
                echo "第{$row}行的商品没有商品详情图，无法导入<br/>";
                continue;
            }

            $third_comments_sale = 0;
            if(!empty($product[11])){
                $third_comments_sale = (int)$product[11];
            }

            // 供货商id 有多个，用/分隔的
            if(strpos($product[0],self::WHOLESALER_IDS_SIGN) !== false){
                $wholesaler_ids = explode(self::WHOLESALER_IDS_SIGN,trim($product[0]));
                foreach($wholesaler_ids as $val){
                    $wholesaler_id = (int)$val;
                    // 查询该平台下商品id 是否已经系统中，如果在，直接弹错
                    $productInfo = Product::find()->select(['id'])->where(['wholesaler_id'=>$wholesaler_id,'third_platform'=>$third_platform,'third_goods_id'=>$third_goods_id,'del'=>1])->asArray()->one();
                    if(!empty($productInfo)){
                        echo "第{$row}行的商品已存在于系统中，请勿重复导入<br/>";
                        continue;
                    }

                    $productModel = new \backend\models\Product();
                    $productModel->unit = "";
                    $productModel->sold_num = "";
                    $productModel->fake_sold_base = 0;
                    $productModel->create_at = $date;
                    $productModel->update_at = $date;
                    $productModel->del = 1;

                    $productModel->wholesaler_id = $wholesaler_id;
                    $productModel->name = $name = $product[2];
                    $productModel->status = $status = $product[1];

                    $productModel->third_params = $third_params = $product[3];
                    $productModel->third_category_id = $third_category_id;

                    $productModel->sort = $sort = $third_comments_sale;
                    $productModel->third_comments_sale = $third_comments_sale;
                    $productModel->third_url = $third_url;
                    $productModel->third_platform = $third_platform;
                    $productModel->third_goods_id = $third_goods_id;
                    $productModel->images = $images;
                    $productModel->description = $description;

                    $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务

                    // 插入数据到 product 表
                    if(!$productModel->save()){
                        $transaction->rollBack();
                        Tools::logException(new Exception(json_encode($productModel->errors)));
                        echo "第{$row}行的商品新增失败".json_encode($productModel->errors)."<br/>";
                        continue;
                    }
                    $product_id = $productModel->id;

                    // 插入数据到 item 表
                    $RetSkusItems = $this->createMulSkusIntoItemsTables($skus_infos,$product_id);
                    if(empty($RetSkusItems)){ // 插入失败
                        Tools::logException(new Exception("items 表插入失败"));
                        $transaction->rollBack();
                    }

                    // 插入数据到 skus 表
                    $RetSku = $this->createMulSkusIntoSkusTables($item_detail,$product_id,$price);
                    if(empty($RetSku)){ // 插入失败
                        Tools::logException(new Exception("规格 表插入失败"));
                        $transaction->rollBack();
                    }
                    $product_ids_arr[] = $product_id;
                    $transaction->commit();
                }

            }else{ // 只有一个
                if (!is_numeric($product[0])) {
                    echo "第{$row}行的供货商id不对--{$product[0]}<br/>";
                    continue;
                }
                $wholesaler_id = $product[0];
                // 查询该平台下商品id 是否已经系统中，如果在，直接弹错
                $productInfo = Product::find()->select(['id'])->where(['wholesaler_id'=>$wholesaler_id,'third_platform'=>$third_platform,'third_goods_id'=>$third_goods_id,'del'=>1])->asArray()->one();
                if(!empty($productInfo)){
                    echo "第{$row}行的商品已存在于系统中，请勿重复导入<br/>";
                    continue;
                }
                // 写入数据库
                $productModel = new \backend\models\Product();
                $productModel->unit = "";
                $productModel->sold_num = "";
                $productModel->fake_sold_base = 0;
                $productModel->create_at = $date;
                $productModel->update_at = $date;
                $productModel->del = 1;

                $productModel->wholesaler_id = $wholesaler_id;
                $productModel->name = $name = $product[2];
                $productModel->status = $status = $product[1];

                $productModel->third_params = $third_params = $product[3];
                $productModel->third_category_id = $third_category_id;

                $productModel->sort = $sort = $third_comments_sale;
                $productModel->third_comments_sale = $third_comments_sale;
                $productModel->third_url = $third_url;
                $productModel->third_platform = $third_platform;
                $productModel->third_goods_id = $third_goods_id;
                $productModel->images = $images;
                $productModel->description = $description;


                $transaction  = Yii::$app->productDb->beginTransaction();  //开启事务

                // 插入数据到 product 表
                if(!$productModel->save()){
                    $transaction->rollBack();
                    Tools::logException(new Exception(json_encode($productModel->errors)));
                    echo "第{$row}行的商品新增失败".json_encode($productModel->errors)."<br/>";
                    continue;
                }
                $product_id = $productModel->id;

                // 插入数据到 item 表
                $RetSkusItems = $this->createMulSkusIntoItemsTables($skus_infos,$product_id);
                if(empty($RetSkusItems)){ // 插入失败
                    Tools::logException(new Exception("items 表插入失败"));
                    $transaction->rollBack();
                }

                // 插入数据到 skus 表
                $RetSku = $this->createMulSkusIntoSkusTables($item_detail,$product_id,$price);
                if(empty($RetSku)){ // 插入失败
                    Tools::logException(new Exception("规格 表插入失败"));
                    $transaction->rollBack();
                }
                $product_ids_arr[] = $product_id;
                $transaction->commit();
            }

            $success++;
        }
        if(!empty($product_ids_arr)) {
            $mqData = [
                'route' => 'taskProduct.productCreateProcess',
                'params' => [
                    'product_id' => $product_ids_arr
                ]
            ];
            $mq = Yii::$app->get('RabbitMQ');
            $mq->publish($mqData);
            echo "<br/>写入到队列成功<br/>";
        }
        echo "<br/>第三方平台的商品导入完成".$success."行成功<br/>";
        exit;


    }

    /**
     * 由第三方平台的url链接，获得平台id ,平台标识：1自已的平台，2淘宝，3京东，4天猫
     * @param $url
     * @return int
     */
    protected function getPlatformByUrl($url){
        $platform = 1;
        foreach ($this->third_platform_arr as $key =>$val){
            if(strpos($url,$key) !== false){
                $platform = $val;
            }
        }
        return $platform;
    }

    /**
     * 由第三方平台导入的sku格式，转化成 items 表所需要的 specification_name 和specification_value
     * 以及 specification 表的 item_detail
     * @param $sku array
     * @ret $res 成功为 数字 ，失败为false
     */
    protected function createMulSkusIntoItemsTables($skus,$product_id=0){
        $date = date("Y-m-d H:i:s");
        $insertData = [];
        if(empty($skus)){
            $insertData[0]['product_id'] = $product_id;
            $insertData[0]['specification_name'] = "无";
            $insertData[0]['specification_value'] = "无";
            $insertData[0]['create_at'] = $date;
            $insertData[0]['del'] = 1;
        }else{
            $j = 0 ;
            foreach ($skus as $key => $val){
                foreach ($val['values'] as $v){
                    $insertData[$j]['product_id'] = $product_id;
                    $insertData[$j]['specification_name'] = $val['label'];
                    $insertData[$j]['specification_value'] = $v['desc'];
                    $insertData[$j]['create_at'] = $date;
                    $insertData[$j]['del'] = 1;
                    $j++;
                }
            }
        }

        $res = Yii::$app->productDb->createCommand()->batchInsert(SpecificationItem::tableName(),
            ['product_id','specification_name','specification_value','create_at','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 第三方平台导入时 插入数据到 skus 表
     * @param $item_detail array
     * @param int $product_id
     * @return mixed
     */
    protected function createMulSkusIntoSkusTables($item_detail,$product_id,$price,$qty=99999){
        $date = date("Y-m-d H:i:s");
        $insertData = [];
        if(empty($item_detail)){
            $j = 0 ;
            $insertData[$j]['product_id'] = $product_id;
            $insertData[$j]['item_detail'] = '{"无":"无"}';
            $insertData[$j]['item_ids'] = "";
            $insertData[$j]['purchase_price'] = $price;
            $insertData[$j]['pick_commission'] = 0;
            $insertData[$j]['promote_commission'] = 0;
            $insertData[$j]['price'] = $price;
            $insertData[$j]['third_current_price'] = $price;
            $insertData[$j]['qty'] = $qty;
            $insertData[$j]['create_at'] = $date;
            $insertData[$j]['barcode'] = \common\tools\Goods::createBarcode();
            $insertData[$j]['del'] = 1;
        }else{
            $j = 0 ;
            foreach ($item_detail as $key => $val){
                $insertData[$j]['product_id'] = $product_id;
                $insertData[$j]['item_detail'] = json_encode($val,JSON_UNESCAPED_UNICODE);
                $insertData[$j]['item_ids'] = "";
                $insertData[$j]['purchase_price'] = $price;
                $insertData[$j]['pick_commission'] = 0;
                $insertData[$j]['promote_commission'] = 0;
                $insertData[$j]['price'] = $price;
                $insertData[$j]['third_current_price'] = $price;
                $insertData[$j]['qty'] = $qty;
                $insertData[$j]['create_at'] = $date;
                $insertData[$j]['barcode'] = \common\tools\Goods::createBarcode();
                $insertData[$j]['del'] = 1;
                $j++;

            }
        }

        $res = Yii::$app->productDb->createCommand()->batchInsert(Specification::tableName(),
            ['product_id','item_detail','item_ids','purchase_price',
                'pick_commission','promote_commission','price','third_current_price','qty','create_at','barcode','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 由第三方平台id 和 第三方url 地址，获取到对应的第三方平台的商品id
     * @param $platform  2淘宝，3京东，4天猫
     * @param $url https://detail.tmall.com/item.htm?id=546045577688&ns
     * @return mixed
     */
    protected function getThirdGoodsId($platform,$url){
        if($platform == 3) {
            $str = basename($url); // 1111.html
            $third_goods_id = explode(".", $str)[0];
        }else if($platform ==2 || $platform == 4){
            $parts = parse_url($url);  //  'query' => string 'id=567543666177' (length=15)
            $idStr = explode("&",$parts['query'])[0];
            $third_goods_id = explode("=", $idStr)[1];
        }
        return $third_goods_id;
    }

    /**
     * 获取导出的查询条件
     * @param $post
     */
    private function _handleExportWHere($post)
    {
        $good_id = intval($post['good_id']);
        $good_name = trim($post['good_name']);
        $good_category_name = trim($post['good_category_name']);
        $good_wholesaler_name = trim($post['good_wholesaler_name']);
        $good_status = intval($post['good_status']);


        if (!$good_id && !$good_name && !$good_category_name && !$good_wholesaler_name) {
            return false;
        }

        $res = [];

        if ($good_id) {
            $res['id'] = $good_id;
            return $res;
        }


        if ($good_status) {
            $res['status'] = $good_status;
        }

        if ($good_name) {
            $res['name'] = $good_name;
        }

        if ($good_category_name) {
            $model = new \backend\models\Category();
            $info = $model::find()->where(['name'=>$good_category_name])->asArray()->one();
            if ($info) {
                $res['third_category_id'] = $info['id'];
            }
        }

        if ($good_wholesaler_name) {
            $model = new \backend\models\Wholesaler();
            $info = $model::find()->where(['name'=>$good_wholesaler_name])->asArray()->one();
            if ($info) {
                $res['wholesaler_id'] = $info['id'];
            }
        }

        return $res;
    }


    /**
     * 上传图片
     * @return array
     */
    public function actionSpecImgUpload()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $files = $_FILES['file'];
            $imgName = explode('.', $files['name']);
            $extension = $imgName[1];

            //后缀名验证
            if (!in_array($extension, ['png','jpg'])) {
                return [
                    'code'=>1,
                    'msg'=> '只支持png和jpg格式！',
                    'data'=>['src'=>'']
                ];
            }

            $fileName = md5($files['name']) . '.' . $extension;
            $result = Ftp::upload($files['tmp_name'], $fileName, 'specification');
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                return [
                    'code'=>1,
                    'msg'=> '上传失败！请重新上传',
                    'data'=>['src'=>'']
                ];
            }
            return [
                'code'=>0,
                'msg'=> '上传成功！',
                'data'=>['src'=>$result['url']]
            ];
        } catch (\Exception $e) {
            return [
                'code'=>1,
                'msg'=> $e->getMessage(),
                'data'=>['src'=>'']
            ];
        }
    }
}
