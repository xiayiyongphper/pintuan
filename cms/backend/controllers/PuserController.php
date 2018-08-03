<?php

namespace backend\controllers;

use Yii;
use backend\models\PintuanUser;
use backend\models\PuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PuserController implements the CRUD actions for PintuanUser model.
 */
class PuserController extends Controller
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
     * Lists all PintuanUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        //搜索昵称
        $nick_name = '';
        if ($params) {
            if (isset($params['PuserSearch']) && isset($params['PuserSearch']['nick_name'])) {
                $nick_name = trim($params['PuserSearch']['nick_name']);
                $params['PuserSearch']['nick_name'] = $nick_name;
            }
        }

        $searchModel = new PuserSearch();
        $dataProvider = $searchModel->search($params);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'nick_name' => $nick_name,
        ]);
    }

    /**
     * Displays a single PintuanUser model.
     * @param string $id
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
     * Creates a new PintuanUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PintuanUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PintuanUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PintuanUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PintuanUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PintuanUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PintuanUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 设置为店主
     */
    public function actionSetstore()
    {
        $post = yii::$app->request->post();
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $uid = intval($post['uid']);
        $sid = intval($post['sid']);

        if ($uid <=0 || $sid<=0) {
            return ['code'=>1,'message'=>'参数错误'];
        }

         $model = new \backend\models\Store();
         $userModel = new \backend\models\PintuanUser();

         $userInfo = $userModel::findOne($uid);
         if (empty($userInfo)) {
             return ['code'=>1,'message'=>'参数错误'];
         }

        if ($userInfo->del == 2) {
            return ['code'=>1,'message'=>'该用户已经被删除了，不能设置'];
        }

        if ($userInfo->is_robot == 2) {
            return ['code'=>1,'message'=>'该用户是机器人，不能设置'];
        }

        if ($userInfo->own_store_id) {
            //return ['code'=>1,'message'=>'您已经设置过，不能重复设置！'];
        }

        $storeInfo = $model::findOne($sid);
        if (empty($storeInfo)) {
            return ['code'=>1,'message'=>'要设置的店铺不存在'];
        }

        $curDate = date('Y-m-d H:i:s', time());
        $data = [
            'own_store_id'=> $sid,
            'updated_at'  => $curDate
        ];
        $res = $userModel::updateAll($data, ['id'=>$uid]);
        if (!$res) {
            return ['code'=>1,'message'=>'设置user表失败，请稍后再来！'];
        }

        $storeLoginModel = new \backend\models\StoreLogin();
        //删除之前的店铺绑定
        $delWhere = [
            'user_id'=>$userInfo->id,
            'del'=>1,
        ];
        $storeLoginList = $storeLoginModel::find()->where($delWhere)->asArray()->one();
        if (!empty($storeLoginList)) {
            $delData = [
                'del'=>2,
                'update_at'=>$curDate
            ];
            $delRes = $storeLoginModel::updateAll($delData, $delWhere);
            if (!$delRes) {
                return ['code'=>1,'message'=>'删除store_login的绑定失败，请稍后再来！'];
            }
        }

        //设置店铺的绑定
        $where = [
            'store_id'=>$sid,
            'user_id'=>$userInfo->id
        ];

        $storeLoginInfo = $storeLoginModel::find()->where($where)->asArray()->one();
        if (empty($storeLoginInfo)) {
            //尚未存在，则进行绑定
            $insertData[] = [
                'union_id'=> $userInfo->union_id,
                'store_id'=> $sid,
                'create_at'=>$curDate,
                'update_at'=>$curDate,
                'del'=>1,
                'user_id'=>$userInfo->id,
            ];
            $fields = [
                'union_id',
                'store_id',
                'create_at',
                'update_at',
                'del',
                'user_id'
            ];
            $res = Yii::$app->wholesalerDb->createCommand()->batchInsert('store_login',$fields, $insertData)->execute();
            if (!$res) {
                return ['code'=>1,'message'=>'设置store_login表失败，请稍后再来！'];
            }
        } else {
            //如果已经存在，则从新绑定
            $setData = [
                'del'=>1,
                'update_at'=>$curDate
            ];
            $setWhere = [
                'id' => $storeLoginInfo['id']
            ];
            $setRes = $storeLoginModel::updateAll($setData, $setWhere);
            if (!$setRes) {
                return ['code'=>1,'message'=>'关联store_login的绑定失败，请稍后再来！'];
            }
        }
        return ['code'=>0,'message'=>'设置成功！'];
    }
}
