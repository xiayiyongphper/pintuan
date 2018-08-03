<?php

namespace backend\controllers;

use backend\models\Topic;
use backend\models\TopicSearch;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * TopicController implements the CRUD actions for Topic model.
 */
class TopicController extends Controller
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
     * Lists all Topic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TopicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Topic model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $selectData = [];
        $topicMsg = '';
        $head = [];
        if ($model->type == 1) {
            $head = ['商品ID','商品名称','供货商'];
            $topicMsg = '专题的商品列表';
            $selectData = $this->_getGoodList($model->products);
        } else {
            $head = ['拼团活动ID','活动名称','商品名称'];
            $topicMsg = '专题的拼团列表';
            $selectData = $this->_getPintuanList($model->products);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'head'=>$head,
            'topicMsg'=>$topicMsg,
            'selectData'=>$selectData
        ]);
    }

    /**
     * Creates a new Topic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Topic();

        $postInfo = Yii::$app->request->post();
        if (!empty($postInfo)) {
            $ids = [];
            $type = intval($postInfo['Topic']['type']);
            $msg = '商品';
            if ($type == 1) {
                $ids = $postInfo['goodids'];
            } else {
                $msg = '拼团活动';
                $ids = $postInfo['topicids'];
            }
            unset($postInfo['goodids'], $postInfo['topicids']);
             if (!$ids) {
                 throw new NotFoundHttpException('请选择' . $msg, 0000);
             }
            $postInfo['Topic']['products'] = implode(',',$ids);
            if ($model->load($postInfo) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
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
     * Updates an existing Topic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $postInfo = Yii::$app->request->post();
        if (!empty($postInfo)) {
            $ids = [];
            $type = intval($postInfo['Topic']['type']);
            $msg = '商品';
            if ($type == 1) {
                $ids = $postInfo['goodids'];
            } else {
                $msg = '拼团活动';
                $ids = $postInfo['topicids'];
            }
            unset($postInfo['goodids'], $postInfo['topicids']);
            if (!$ids) {
                throw new NotFoundHttpException('请选择' . $msg, 0000);
            }
            $postInfo['Topic']['products'] = implode(',',$ids);

            if ($model->load($postInfo) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $p1 = $p2 = [];
        if ($model->img_url) {
            $p1 = $model->img_url;
            $p2 = [
                'url' => \yii\helpers\Url::to(['/topic/image-delete']),
                'key' => $model->img_url,
            ];
        }

        $selectData = [];
        if ($model->type == 1) {
            $selectData = $this->_getGoodList($model->products);
        } else {
            $selectData = $this->_getPintuanList($model->products);
        }

        return $this->render('update', [
            'p1' => $p1,
            'p2' => $p2,
            'model' => $model,
            'selectData'=>$selectData
        ]);
    }

    /**
     * Deletes an existing Topic model.
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
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImageUpload()
    {
        try {
            $model = new Topic();
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

    /**
     * 获取商品列表
     */
    private function _getGoodList($idsStr)
    {
       $model = new \backend\models\Product();
       $wholesalerModel = new \backend\models\Wholesaler();

       $prodIds = explode(',', $idsStr);
       $where = [
           'in','id', $prodIds
       ];

       $select = 'product.id,product.name,product.wholesaler_id';
       $data = $model::find()->where($where)->asArray()->all();

       if ($data) {
            foreach ($data as $key=>$val) {
                $wholesalerInfo = $wholesalerModel::findOne($val['wholesaler_id']);
                $data[$key]['wholesaler_name'] = $wholesalerInfo->name;
            }
       }
       return $data;
    }

    /**
     * 获取拼团列表
     */
    private function _getPintuanList($idsStr)
    {
        $model = new \backend\models\PintuanActivity();

        $idArr = explode(',', $idsStr);
        $where = [
            'in','pintuan_activity.id', $idArr
        ];

        $select = 'pintuan_activity.id,pintuan_activity.title,product.name';
        $data = $model::find()->select($select)->leftJoin('product','product.id=pintuan_activity.product_id')->where($where)->asArray()->all();
        return $data;
    }
}
