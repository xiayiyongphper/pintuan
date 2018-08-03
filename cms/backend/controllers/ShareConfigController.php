<?php

namespace backend\controllers;

use backend\models\ShareConfig;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * ShareConfigController implements the CRUD actions for ShareConfig model.
 */
class ShareConfigController extends Controller
{
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
     * Lists all ShareConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ShareConfig::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShareConfig model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ShareConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShareConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $p1 = $p2 = [];
        $model->type = 1;
        $model->position = 1;
        $model->status = 1;
        return $this->render('create', [
            'p1' => $p1,
            'p2' => $p2,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShareConfig model.
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
                'url' => \yii\helpers\Url::to(['/share-config/image-delete']),
                'key' => $model->img_url,
            ];
        }
        return $this->render('update', [
            'p1' => $p1,
            'p2' => $p2,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ShareConfig model.
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
     * Finds the ShareConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShareConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShareConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImageUpload()
    {
        try {
            $model = new ShareConfig();
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
