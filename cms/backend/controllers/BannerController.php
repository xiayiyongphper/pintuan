<?php

namespace backend\controllers;

use backend\models\Banner;
use backend\models\BannerSearch;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banner model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //根据banner类型获取选择的商品、拼团、专题
        $prodcutModel = new \backend\models\Product();
        $selectData = [];
        $bannerMsg = '';
        $head = [];
        if ($model->type == 1) {
            $bannerMsg = 'banner类型:商品详情';
            $head = ['商品ID','商品名称'];
            $goodInfo = $prodcutModel::findOne($model->value);
            $selectData[] = [
                'id'=>$goodInfo->id,
                'title'=>$goodInfo->name,
            ];
        } else if ($model->type == 2) {
            $bannerMsg = 'banner类型:拼团详情';
            $head = ['拼团活动ID','拼团活动名称','商品名称'];
            $pintuanActModel = new \backend\models\PintuanActivity();
            $pintuanInfo = $pintuanActModel::findOne($model->value);
            $goodInfo = $prodcutModel::findOne($pintuanInfo->product_id);
            $selectData[] = [
                'id'=>$pintuanInfo->id,
                'title'=>$pintuanInfo->title,
                'productName'=>$goodInfo->name,
            ];
        } else if ($model->type == 3) {
            $bannerMsg = 'banner类型:专题';
            $head = ['专题活动ID','专题活动名称'];
            $topticModel = new \backend\models\Topic();
            $topticInfo = $topticModel::findOne($model->value);
            $selectData[] = [
                'id'   => $topticInfo->id,
                'title'=> $topticInfo->title,
            ];
        }

        return $this->render('view', [
            'model' => $model,
            'bannerMsg'=>$bannerMsg,
            'head'=>$head,
            'selectData'=>$selectData
        ]);
    }

    /**
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $p1 = $p2 = [];
        $model->type = 1;
        $model->status = 1;
        return $this->render('create', [
            'p1' => $p1,
            'p2' => $p2,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $p1 = $p2 = [];
        if ($model->img_url) {
            $p1 = $model->img_url;
            $p2 = [
                'url' => \yii\helpers\Url::to(['/topic/image-delete']),
                'key' => $model->img_url,
            ];
        }

        //根据banner类型获取选择的商品、拼团、专题
        $prodcutModel = new \backend\models\Product();
        $selectData = [];
        if ($model->type == 1) {
              //商品
            $goodInfo = $prodcutModel::findOne($model->value);
            $selectData[] = [
                'id'=>$goodInfo->id,
                'productName'=>$goodInfo->name,
            ];
        } else if ($model->type == 2) {
              //拼团
              $pintuanActModel = new \backend\models\PintuanActivity();
              $pintuanInfo = $pintuanActModel::findOne($model->value);
              $goodInfo = $prodcutModel::findOne($pintuanInfo->product_id);
              $selectData[] = [
                  'id'=>$pintuanInfo->id,
                  'title'=>$pintuanInfo->title,
                  'productName'=>$goodInfo->name,
             ];
        } else if ($model->type == 3) {
            //专题
            $topticModel = new \backend\models\Topic();
            $topticInfo = $topticModel::findOne($model->value);
            $selectData[] = [
                'id'   => $topticInfo->id,
                'title'=> $topticInfo->title,
            ];
        }
        return $this->render('update', [
            'p1' => $p1,
            'p2' => $p2,
            'model' => $model,
            'selectData'=>$selectData
        ]);
    }

    /**
     * Deletes an existing Banner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImageUpload()
    {
        try {
            $model = new Banner();
            $imageFile = UploadedFile::getInstance($model, 'image');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName);
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                throw new \Exception($result['msg'], $result['code']);
            }
            return json_encode([
                'files' => [
                    [
                        'name' => $fileName,
                        'url' => $result['url'],
                        'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Tools::log($e, 'topic_exception.log');
        } catch (\Error $e) {
            Tools::log($e, 'topic_error.log');
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
}
