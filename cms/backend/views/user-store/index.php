<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Store;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =  '【' . $nickName . '】的自提点管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'label'=>'设置为店主',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $user = new \backend\models\PintuanUser();
                    $info = $user::findOne($model->user_id);
                    if ($info->own_store_id == $model->store_id) {
                        $checked = true;
                        $dislay_msg = '当前为：店主';
                    } else {
                        $checked = false;
                        $dislay_msg = '当前为：普通用户';
                    }
                    return  '<label>' . Html::radio('user_store_id', $checked, ['class'=>'zitidian','value'=>$model->id,'storeid'=>$model->store_id,'userid'=>$model->user_id]) . $dislay_msg . '</label>';
                }
            ],
            [
                'label'=>'店铺自提点信息',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $store = new \backend\models\Store();
                    $storeInfo = $store::findOne($model->store_id);
                    if (!$storeInfo) {
                        return '';
                    }
                    return $storeInfo->name . '，电话：' . $storeInfo->store_phone . '，详细地址：' . $storeInfo->address . $storeInfo->detail_address;
                }
            ],
        ],
    ]); ?>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script>
    //设置为店铺
    function setStore(user_id,store_id)
    {
        //确定是否设置
        if (!window.confirm('确定要设置吗？')) {
                setTimeout(function(){
                    window.location.reload();
                }, 300);
                return;
        }
        var url = "<?php echo \yii\helpers\Url::toRoute(['puser/setstore']) ?>";
        var param = {
            uid: user_id,
            sid : store_id
        };
        $.ajax({
            type: 'post',
            url: url,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    alert('设置成功！');
                    setTimeout(function(){
                        window.location.reload();
                    }, 500)
                } else {
                    alert(res.message);
                    setTimeout(function(){
                        window.location.reload();
                    }, 500)
                }
            }
        });
    }
    $(function(){
         $(".zitidian").change(function(){
               var id = $(this).val();
               var user_id = $(this).attr('userid');
               var store_id = $(this).attr('storeid');
               setStore(user_id,store_id);
         });
    });
</script>
