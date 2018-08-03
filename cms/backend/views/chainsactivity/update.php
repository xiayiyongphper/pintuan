<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '编辑接龙';
$this->params['breadcrumbs'][] = ['label' => '接龙活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<form class="layui-form" action="" method="post" lay-filter="example">
    <blockquote class="site-text layui-elem-quote" style="margin:0;margin-top:-15px;padding: 10px;border-left: 5px solid #009688">
        编辑接龙活动
    </blockquote>
    <table class="layui-table" style="margin: 0;">
        <tr>
            <td width="8%">活动名称</td>
            <td colspan="3">
                <input type="hidden" name="id" id="id" value="">
                <input type="text" name="title" lay-verify="required" maxlength="30" autocomplete="off" placeholder="请输入活动名称" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="6%">权重</td>
            <td colspan="3">
                <input type="text" name="weight" lay-verify="required|number" maxlength="8" autocomplete="off" value="1" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="10%">起止时间</td>
            <td>
                <input type="text" name="date" id="date1" lay-verify="required" placeholder="点击选择起止时间" autocomplete="off" class="layui-input">
            </td>
            <td width="10%">活动状态</td>
            <td>
                <input type="radio" name="status" value="1" title="未结束" checked="checked">
                <input type="radio" name="status" value="2" title="已结束">
            </td>
        </tr>
        <tr>
            <td width="8%">自提点选择</td>
            <td colspan="3">
                <input type="radio" name="place_type" value="1" lay-filter="place_type" title="同供货商配送范围" checked="checked">
                <input type="radio" name="place_type" value="2" lay-filter="place_type" title="手动选择自提点">
            </td>
        </tr>
        <tr id="zitidian_box" style="display:<?php echo $info->place_type==2? '' : 'none';?>;">
            <td width="10%">
                活动自提点
            </td>
            <td colspan="3">
                <button id="chooseStoreBtn" type="button" class="layui-btn layui-btn-normal" lay-filter="chooseStoreBtn">
                    <i class="layui-icon">&#xe608;</i> 添加活动自提点
                </button>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>店铺ID</th>
                        <th>店铺名称</th>
                        <th>店主名字</th>
                        <th>联系电话</th>
                        <th>城市</th>
                        <th>地址</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="storebox">
                    <?php if ($storeList) { ?>
                        <?php foreach ($storeList as $store) { ?>
                            <tr id="store_<?php echo $store['store_id'];?>">
                                <td><?php echo $store['store_id'];?></td>
                                <td><input type="hidden" name="storeids[]" value="<?php echo $store['store_id'];?>"><?php echo $store['store_name'];?></td>
                                <td><?php echo $store['owner_user_name'];?></td>
                                <td><?php echo $store['store_phone'];?></td>
                                <td><?php echo $store['city_name'];?></td><td><?php echo $store['address'];?></td>
                                <td><button class="layui-btn" onclick="delguige(this)">移除</button></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="10%">
                活动商品
            </td>
            <td colspan="3">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>规格ID</th>
                        <th>商品名称</th>
                        <th>规格</th>
                        <th>活动库存</th>
                        <th>接龙价格</th>
                        <th>限购数量</th>
                        <th>默认假销量</th>
                    </tr>
                    </thead>
                    <tbody id="goodbox">
                    <?php if ($goodList) { ?>
                        <?php foreach ($goodList as $good) { ?>
                            <tr>
                                <td>
                                    <?php echo $good['specification_id'];?>
                                    <input type="hidden" name="specification_id[]" value="<?php echo $good['specification_id'];?>">
                               </td>
                                <td><?php echo $good['name'];?></td>
                                <td><?php echo json_encode(json_decode($good['item_detail'],true),JSON_UNESCAPED_UNICODE); ?></td>
                                <td><input type="text" name="qty[]" value="<?php echo $good['qty'];?>" lay-verify="required|number" maxlength="8" autocomplete="off" class="layui-input"></td>
                                <td><input type="text" name="activity_price[]" value="<?php echo sprintf("%.2f", $good['activity_price'] /100);?>" lay-verify="required|number" maxlength="7" autocomplete="off" class="layui-input"></td>
                                <td><input type="text" name="fake_sold_base[]" value="<?php echo $good['fake_sold_base'];?>" lay-verify="required|number" maxlength="8" autocomplete="off" class="layui-input"></td>
                                <td><input type="text" name="limit_buy_num[]" value="<?php echo $good['limit_buy_num'];?>" lay-verify="required|number" maxlength="8" autocomplete="off" class="layui-input"></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="8%">封面图片</td>
            <td colspan="3">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="image_btn">上传封面</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="image_display" src="<?php echo $info['image'];?>">
                        <input type="hidden" id="image" name="image" value="<?php echo $info['image'];?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr><td></td><td colspan="3"><button class="layui-btn" lay-submit="" lay-filter="submit">保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button></td></tr>
    </table>
</form>
<script src="../../layui/layui.js"></script>
<script>
    var good_url = "<?php echo \yii\helpers\Url::toRoute(['choose/goodspe']);?>";
    var store_url = "<?php echo \yii\helpers\Url::toRoute(['choose/store']);?>";
    var upload_url = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/upload']);?>";

    function delguige(obj)
    {
        $(obj).parent().parent().remove();
    }
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            if (len >=2) {
                layer.msg('亲，只能选择一个商品规格！', {icon: 5});
                return;
            }

            var good = dataList[0];

            var priceInput = '<td><input type="text" name="activity_price[]" lay-verify="required|number" maxlength="7" autocomplete="off" placeholder="0.00" class="layui-input"></td>';
            var qtyInput = '<td><input type="text" name="qty[]" lay-verify="required|number" maxlength="8" autocomplete="off" placeholder="0" class="layui-input"></td>';
            var fakeInput = '<td><input type="text" name="fake_sold_base[]" lay-verify="required|number" maxlength="8" autocomplete="off" placeholder="0" class="layui-input"></td>';
            var limitInput = '<td><input type="text" name="limit_buy_num[]" value="0" lay-verify="required|number" maxlength="8" autocomplete="off" placeholder="0" class="layui-input"></td>';

            var html = '<tr><td>'+good.id+'</td><td><input type="hidden" name="specification_id[]" value="'+good.id+'">'+good.name+'</td><td>'+good.item_detail+'</td>'+ qtyInput + priceInput+ fakeInput + limitInput + '</tr>';
            $("#goodbox").html(html);
        }
    }

    function getStores(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            var html = '';
            for (var i=0; i<len;i++) {
                var curId = 'store_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td>'+dataList[i].id+'</td><td><input type="hidden" name="storeids[]" value="'+dataList[i].id+'">'+dataList[i].name+'</td><td>'+dataList[i].owner_user_name+'</td><td>'+dataList[i].store_phone+'</td><td>'+dataList[i].city_name+'</td><td>'+dataList[i].address+'</td><td><button class="layui-btn" onclick="delguige(this)">移除</button></td></tr>';
            }

            $("#storebox").append(html);
        }
    }

    layui.use(['form', 'layedit', 'laydate','upload'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate
            ,upload = layui.upload;

        //日期
        laydate.render({
            elem: '#date1'
            ,type: 'datetime'
            ,range:'~'
        });

        //表单初始赋值
        form.val('example', {
            "id": "<?php echo $info['id'];?>"
            ,"title": "<?php echo $info['title'];?>"
            ,"date":"<?php echo $info['start_time'];?> ~ <?php echo $info['end_time'];?>"
            ,"place_type": "<?php echo $info['place_type'];?>"
            ,"weight": "<?php echo $info['weight'];?>"
            ,"status": "<?php echo $info['status'];?>"
        });


        //招募团长banner图片
        var uploadInst = upload.render({
            elem: '#image_btn'
            ,url: upload_url
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    //$('#invite_colonel_banner_img').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 1){
                    return layer.msg(res.msg);
                } else {
                    //上传成功
                    $('#image_display').attr('src', res.data.src);
                    $('#image').val(res.data.src);
                }
            }
            ,error: function(){
            }
        });

        //监听自提点的选择
        form.on('radio(place_type)', function(data){
            if (data.value == 2) {
                $("#zitidian_box").show();
            } else {
                $("#zitidian_box").hide();
            }
        });

        //监听提交
        form.on('submit(submit)', function(data){
            if (data.field.place_type == 2) {
                var storeLen = $('#storebox').find('tr').size();
                if (storeLen == 0) {
                    layer.msg('请选择活动自提点！', {icon: 5});
                    $('#chooseStoreBtn').focus();
                    return false;
                }
            }

            var goodsLen = $('#goodbox').find('tr').size();
            if (goodsLen == 0) {
                layer.msg('请选择商品规格！', {icon: 5});
                $('#chooseGoodBtn').focus();
                return false;
            }

            var url = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/add']);?>";
            var param = data.field;
            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        var index_url = "<?php echo \yii\helpers\Url::toRoute(['chainsactivity/index']);?>";
                        layer.msg(res.message, {icon: 1});
                        setTimeout(function(){
                            window.location = index_url;
                        }, 1200);
                    } else {
                        layer.msg(res.message, {icon: 5});
                    }
                }
            });
            return false;
        });

        //选择商品
        $("#chooseGoodBtn").click(function(){
            layer.open({
                type: 2,
                title:'选择商品规格【只能选择一个sku】',
                area: ['70%', '90%'],
                content: good_url
            });
            return false;
        });

        //选择自提点
        $("#chooseStoreBtn").click(function(){
            layer.open({
                type: 2,
                title:'选择活动自提点',
                area: ['70%', '90%'],
                content: store_url
            });
            return false;
        });
    });
</script>