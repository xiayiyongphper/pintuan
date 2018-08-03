<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '商品管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', '修改'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', '删除'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', '是否确定删除商品?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'sub_name',
            'wholesaler_id',
            'status',
            //'images',
            'fake_sold_base',
            'unit',
            'sort',
            'create_at',
            'update_at',
            'third_category_id',
            'del',
        ],
    ]) ?>

    <table id="w0" class="table table-striped table-bordered detail-view">
        <tbody>
           <tr><td>商品图片</td></tr>
           <tr><td>
                   <?php
                       $images_arr = [];
                       if ($model->images) {
                           $images_arr = explode(';', $model->images);
                       }
                   ?>
                   <?php if ($images_arr) { ?>
                       <?php foreach ($images_arr as $val) {?>
                       <img src="<?php echo $val;?>" width="200" alt="">
                       <?php } ?>
                   <?php } ?>
               </td></tr>
        </tbody>
     </table>

    <table id="w0" class="table table-striped table-bordered detail-view">
        <tbody>
        <tr><td>商品描述</td></tr>
        <tr><td>
                <?php
                $description_arr = [];
                if ($model->description) {
                    $description_arr = explode(';', $model->description);
                }
                ?>
                <?php if ($description_arr) { ?>
                    <?php foreach ($description_arr as $desc) {?>
                        <img src="<?php echo $desc;?>" width="200" alt="">
                    <?php } ?>
                <?php } ?>
            </td></tr>
        </tbody>
    </table>
       <form id="specificationForm">
        <table class="table table-condensed">
             <thead>
              <th>ID</th>
              <th>规格编码</th>
              <th>规格属性</th>
              <th>规格图片</th>
              <th>进货价格(元)</th>
              <th>自提佣金(元)</th>
              <th>售价(元)</th>
              <th>库存</th>
              <th>状态</th>
              <th>操作</th>
             </thead>
            <tbody>
            <?php if ($specificationList) { ?>
                <?php foreach ($specificationList as $k=>$val) { ?>
                       <tr>
                           <td><?php echo $val['id'];?><input type="hidden" name="ids[]" value="<?php echo $val['id'];?>"></td>
                           <td><?php echo $val['barcode'];?></td>
                           <td>
                               <?php
                               $attr = json_decode($val['item_detail'],true);
                               $str = '';
                               foreach ($attr as $k => $v){
                                   $str .= "$k:$v;";
                               }
                               echo $str;
                               ?>
                           </td>
                           <td>
                               <div class="layui-upload">
                                   <button type="button" class="layui-btn">上传图片</button>
                                   <input type="hidden" name="image[]" value="<?=$val['image']?>">
                                   <div class="layui-upload-list">
                                       <img class="layui-upload-img" src="<?=$val['image']?>">
                                   </div>
                               </div>
                           </td>
                           <td>
                               <input type="text" name="purchase_price[]" value="<?php echo sprintf("%.2f",$val['purchase_price'] / 100);?>">
                           </td>
                           <td>
                               <input type="text" name="pick_commission[]" value="<?php echo sprintf("%.2f",$val['pick_commission'] / 100);?>">
                           </td>
                           <td>
                               <input type="text" name="price[]" value="<?php echo sprintf("%.2f",$val['price'] / 100);?>">
                           </td>
                           <td>
                               <input type="text" name="qty[]" value="<?php echo $val['qty'];?>">
                           </td>
                           <td class="status-lable">
                               <?php if ($val['del'] == 1) { ?>
                                   <span style="color: blue">启用</span>
                               <?php } else { ?>
                                   <span style="color: red">停用</span>
                               <?php }  ?>
                           </td>
                           <td class="">
                               <?php if ($val['del'] == 1) { ?>
                                   <input type="button" value="停用" data-id="<?php echo $val['id'];?>" data-del="2" class="delspe" onclick="setSpeStatus(this)">
                               <?php } else { ?>
                                   <input type="button" value="启用" data-id="<?php echo $val['id'];?>" data-del="1" class="respe" onclick="setSpeStatus(this)">
                               <?php }  ?>
                           </td>
                       </tr>
                <?php } ?>
                <tr><td colspan="7" align="center"><input type="button" id="updateBtn" value="更新规格数据"></td></tr>
            <?php } ?>
            </tbody>
        </table>
       </form>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script src="../../layui/layui.js"></script>
<script>
    layui.use('upload', function(){
        var $ = layui.jquery
            ,upload = layui.upload;

        //普通图片上传
        var uploadInst = upload.render({
            elem: '.layui-upload'
            ,accept: 'image'
            ,url: '<?=\yii\helpers\Url::to(['/product/spec-img-upload'])?>'
            ,before: function(obj){
                var img = this.item.children("div").children("img");
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    img.attr('src', result);
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }

                var input = this.item.children("input");
                input.attr('value', res.data.src);

            }
            ,error: function(){
                return layer.msg('上传失败');
            }
        });
    });

    //启用停用方法
    function setSpeStatus(obj)
    {
        var dom = $(obj);
        var id = dom.attr('data-id');
        var del = dom.attr('data-del');

        dom.attr("disabled","disabled");

        if (del == 1) {
            var afterDel = 2;
            var afterMsg = '停用';
            var msg = '启用';
            var curMsg = '<span style="color: blue">启用</span>';
        } else {
            var afterDel = 1;
            var afterMsg = '启用';
            var msg = '停用';
            var curMsg = '<span style="color: red">停用</span>';
        }

        if (!window.confirm('确定要'+msg+'吗？')) {
                 return;
        }

        var url = "<?php echo \yii\helpers\Url::toRoute(['product/updatespedel']) ?>";
        var param = {
            id : id,
            del : del
        };
        $.ajax({
            type: 'post',
            url: url,
            data: param,
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    dom.attr('data-del', afterDel);
                    dom.val(afterMsg);
                    dom.parent().siblings('.status-lable').html(curMsg);
                    alert(msg + '成功！');
                } else {
                    alert(res.message);
                }
                dom.removeAttr("disabled");
            }
        });
    }
    $(function(){
        $('#updateBtn').click(function(){
            var param = $("#specificationForm").serialize();

            var url = "<?php echo \yii\helpers\Url::toRoute(['product/updatespe']) ?>";
            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        alert('设置成功！');
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    });
</script>