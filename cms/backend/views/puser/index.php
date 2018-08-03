<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pintuan-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pintuan-user-search">
        <form id="w0" action="" method="get">
            <div class="form-group field-pusersearch-id has-success">
                <label class="control-label" for="pusersearch-nick_name">用户昵称</label>
                <input type="text" id="pusersearch-nick_name" class="form-control" name="PuserSearch[nick_name]" aria-invalid="false" value="<?php echo $nick_name;?>">
                <div class="help-block"></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">搜索</button></div>
        </form>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'真实姓名',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->real_name;
                }
            ],
            [
                'attribute'=>'昵称',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->nick_name;
                }

            ],
            [
                'attribute'=>'性别',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->gender == 1) {
                        return '男';
                    } else if ($model->gender == 2) {
                        return '女';
                    } else {
                        return '未知';
                    }
                }
            ],
            [
                'attribute'=>'城市',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->city;
                }
            ],
            [
                'attribute'=>'国家',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    return $model->country;
                }

            ],
            [
                'attribute' => '头像',
                'format' => ['image', ['width' => '120']],
                'value' => function ($model) {
                    return $model->avatar_url;
                },
            ],
            [
                'attribute'=>'是否下单',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    if ($model->has_order==1) {
                        return '是';
                    } else if ($model->has_order==2) {
                        return '否';
                    }
                }
            ],
            [
                'attribute'=>'创建时间',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                     return $model->created_at;
                }

            ],
            [
                'attribute'=>'操作',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model){
                    $url = \yii\helpers\Url::toRoute('/user-store/index') . '?UserStoreSearch[user_id]=' . $model->id;
                    $html = "<a href='$url'>自提点/设置店主</a>";
                    return $html;
                }

            ],
            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
</div>
<div>
     <!--千万不能删除-->
    <input type="hidden" id="hid_user_id" value="0">
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layer-v3.1.1/layer.js"></script>
<script id="temp" type="text/html">
    <table class="table table-hover">
        <tr><td>店铺id</td><td><input type="text" name="store_id" id="store_id" placeholder="输入店铺id"></td></tr>
    </table>
    <lable></lable>
</script>

<script>
     //设置为店铺
     function setStore(uid,sid)
     {
         var url = "<?php echo \yii\helpers\Url::toRoute(['puser/setstore']) ?>";
         var param = {
             uid: uid,
             sid : sid
         };
         $.ajax({
             type: 'post',
             url: url,
             data: param,
             dataType: "json",
             success: function (res) {
                  if (res.code == 0) {
                      layer.msg('设置成功！');
                      setTimeout(function(){
                          window.location.reload();
                      }, 500)
                  } else {
                      layer.msg(res.message, {icon: 5});
                  }
             }
         });
     }
    $(function(){
        $("#w0-filters").remove();
        $(".set_store_btn").click(function(){
            var obj = $(this);
            $("#hid_user_id").val(obj.attr('id'));

          layer.open({
                content: $("#temp").html()
                ,btn: ['确定', '关闭']
                ,yes: function(index, layero){
                    //按钮【按钮一】的回调
                    var sid = $("#store_id").val();
                    if (!sid) {
                        alert('请输入店铺id');
                        return;
                    }
                    var user_id = $("#hid_user_id").val();
                    layer.closeAll();
                    setStore(user_id,sid);
                }
                ,btn2: function(index, layero){
                }
                ,cancel: function(){
                }
            });
        });
    });
</script>
