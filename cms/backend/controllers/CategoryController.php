<?php

namespace backend\controllers;

use backend\models\Category;
use backend\models\CategorySearch;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        $p1 = $p2 = [];
        if (!$model->load(Yii::$app->request->post())) {
            return $this->render('create', [
                'p1' => $p1,
                'p2' => $p2,
                'model' => $model,
            ]);
        }

        $post = Yii::$app->request->post('Category');
        $name = $post['name'];

//        $info = $model::find()->where(['name'=>$name])->asArray()->one();
//        if (!empty($info)) {
//            echo '该分类名称已存在！';
//            exit;
//        }

        if (!$model->parent_id) {
            $model->parent_id = 1;
        }

        $parentCategory = Category::findOne(['id' => $model->parent_id]);
        $model->level = $parentCategory->level + 1;

        //获取上传的最后一张图片
        if (isset($post['img']) && $post['img']) {
            $imgArr = explode(";", $post['img']);
            $count = count($imgArr);
            if ($count >= 2) {
                $model->img = $imgArr[$count-1];
            } else {
                $model->img = $imgArr[0];
            }
        }

        if (!$model->save()) {
            Tools::logException(new Exception(json_encode($model->errors)));
            throw new \RuntimeException('新增失败');
        }

        $model->path = $parentCategory->path . "/" . $model->id;

        if (!$model->save()) {
            Tools::logException(new Exception(json_encode($model->errors)));
            throw new \RuntimeException('新增失败');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->load(Yii::$app->request->post())) {
            $p1 = $p2 = [];
            if (!empty($model->img)) {
                $p1[] = $model->img;
                $p2[] = [
                    'url' => \yii\helpers\Url::to(['/category/image-delete']),
                    'key' => $model->img,
                ];
            }
            Tools::log($model->toArray(), 'category.log');

            return $this->render('update', [
                'p1' => $p1,
                'p2' => $p2,
                'model' => $model,
            ]);
        }

        //获取上传的最后一张图片
        $post = yii::$app->request->post();
        if (isset($post['img']) && $post['img']) {
            $imgArr = explode(";", $post['img']);
            $count = count($imgArr);
            if ($count >= 2) {
                $model->img = $imgArr[$count-1];
            } else {
                $model->img = $imgArr[0];
            }
        }

        if (!$model->save()) {
            Tools::logException(new Exception(json_encode($model->errors)));
            throw new \RuntimeException('更新失败');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = intval($id);
        //是否有父分类
        $model = new \backend\models\Category();
        $parent = $model::find()->where(['parent_id'=>$id])->asArray()->one();
        if ($parent) {
            echo '该分类有子分类，不能删除';
            exit;
        }

        //是否商品存在
        $pro_model = new \backend\models\Product();
        $product_info = $pro_model::find()->where(['third_category_id'=>$id])->asArray()->one();
        if ($product_info) {
            echo '该分类有商品，不能删除';
            exit;
        }

        $info = $this->findModel($id);
        $info->del = 2;
        $info->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionSelectList($q = '')
    {
        $req = \Yii::$app->getRequest();
        if (!$req->getIsAjax()) {
            return $this->redirect('/site/error');
        }

        $out = ['results' => []];
        if (!empty($q)) {
            $models = Category::find()->where(['like', 'name', $q])->andWhere(['in', 'level', [1, 2]])->all();
            if ($models) {
                foreach ($models as $model) {
                    $out['results'][] = [
                        'id' => $model->id,
                        'text' => $model->name
                    ];
                }
            }
        }

        return json_encode($out);
    }

    public function actionSelectThirdLevelList($q = '')
    {
        $req = \Yii::$app->getRequest();
        if (!$req->getIsAjax()) {
            return $this->redirect('/site/error');
        }

        $out = ['results' => []];
        if (!empty($q)) {
            $models = Category::find()->where(['like', 'name', $q])->andWhere(['level' => 3])->all();
            if ($models) {
                foreach ($models as $model) {
                    $out['results'][] = [
                        'id' => $model->id,
                        'text' => $model->name
                    ];
                }
            }
        }

        return json_encode($out);
    }

    /**
     * ajax获取分类
     * @return string
     */
    public function actionCatelist()
    {
        $get = yii::$app->request->get();
        $req = \yii::$app->getRequest();

        $res = ['results' => []];
        $id = intval($get['id']);
        $level = intval($get['level']);

        if (!$req->getIsAjax()) {
            return json_encode($res);
            exit;
        }

        $where = [];
        if ($id) {
            $where['parent_id'] = $id;
        }
        if (in_array($level, [1,2,3])) {
            $where['level'] = $level;
        }

        $dataList = Category::find()->where($where)->asArray()->all();
        if ($dataList) {
            foreach ($dataList as $val) {
                $res['results'][] = [
                    'id' => $val['id'],
                    'name' => $val['name']
                ];
            }
        }

        return json_encode($res);
        exit;
    }

    public function actionImageUpload()
    {
        try {
            //Tools::log($_FILES,'cate.log');
            $model = new Category();
            $imageFile = UploadedFile::getInstance($model, 'image');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'category', true);
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
}
