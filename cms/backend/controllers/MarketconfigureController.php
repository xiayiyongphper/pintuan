<?php

namespace backend\controllers;

use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\tools\Ftp;
use yii\web\UploadedFile;
use backend\models\MarketConfigure;

/**
 * 乐小拼--市场推广运营--配置
 */
class MarketconfigureController extends Controller
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
     * 配置
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        $config = MarketConfigure::find()->asArray()->one();

        if ($config) {
            $data = [
                'id'=>$config['id'],
                'custom_nickname'=>$config['custom_nickname'],
                'custom_qrcode'=>$config['custom_qrcode'],
                'solitaire_success_msg'=>$config['solitaire_success_msg'],
                'invite_btn_msg'=>$config['invite_btn_msg'],
                'invite_colonel_banner'=>$config['invite_colonel_banner'],
                'colonel_describe_img'=>$config['colonel_describe_img'],
            ];
        } else {
            $data = [
                'id'=>0,
                'custom_nickname'=>'',
                'custom_qrcode'=>'',
                'solitaire_success_msg'=>'',
                'invite_btn_msg'=>'',
                'invite_colonel_banner'=>'',
                'colonel_describe_img'=>'',
            ];
        }

        return $this->render('index',$data);
    }

    /**
     * 新增编辑
     * @return array
     */
    public function actionAdd()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = yii::$app->request->post();
        $model = new MarketConfigure();

        $id = isset($post['configure_id'])?  intval($post['configure_id']): 0;
        $custom_nickname = isset($post['custom_nickname'])? $post['custom_nickname'] : '';
        $custom_qrcode = isset($post['custom_qrcode'])? $post['custom_qrcode'] : '';
        $solitaire_success_msg = isset($post['solitaire_success_msg'])? $post['solitaire_success_msg'] : '';
        $invite_btn_msg = isset($post['invite_btn_msg'])? $post['invite_btn_msg'] : '';
        $invite_colonel_banner = isset($post['invite_colonel_banner'])? $post['invite_colonel_banner'] : '';
        $colonel_describe_img = isset($post['colonel_describe_img'])? $post['colonel_describe_img'] : '';

        if (empty($solitaire_success_msg)) {
            return ['code'=>1,'message'=>'请输入接龙成功文案'];
        }

        if (empty($invite_btn_msg)) {
            return ['code'=>1,'message'=>'请输入邀请按钮文案'];
        }

        if (empty($invite_colonel_banner)) {
            return ['code'=>1,'message'=>'请上传招募团长的banner图片'];
        }

        if (empty($colonel_describe_img)) {
            return ['code'=>1,'message'=>'请上传招募团长的详情图片'];
        }

        if (empty($custom_qrcode)) {
            return ['code'=>1,'message'=>'请上传客服二维码'];
        }

        if (empty($custom_nickname)) {
            return ['code'=>1,'message'=>'请填写客服昵称'];
        }

        if ($id) {
            $model = MarketConfigure::findOne($id);
            if (!$model) {
                return ['code' => 1, 'message' => '要编辑的记录不存在！'];
            }
        } else {
            $info = MarketConfigure::find()->one();
            if ($info) {
                $model = MarketConfigure::findOne($info->id);
            }
        }

        $curDate = date('Y-m-d H:i:s', time());
        $model->custom_nickname = $custom_nickname;
        $model->custom_qrcode = $custom_qrcode;
        $model->solitaire_success_msg = $solitaire_success_msg;
        $model->invite_btn_msg = $invite_btn_msg;
        $model->invite_colonel_banner = $invite_colonel_banner;
        $model->colonel_describe_img = $colonel_describe_img;
        $model->updated_at = $curDate;

        if (!$id) {
            $model->created_at = $curDate;
        }
        $result = $model->save();

        if (!$result) {
            return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=>'设置成功'];
    }

    /**
     * 上传图片
     * @return array
     */
    public function actionUpload()
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
            $result = Ftp::upload($files['tmp_name'], $fileName, 'marketconfigure');
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
                'msg'=> '上传失败！请重新上传',
                'data'=>['src'=>'']
            ];
        }
    }
}
