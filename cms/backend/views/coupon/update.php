<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '编辑优惠券';
$this->params['breadcrumbs'][] = ['label' => '优惠券管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<form class="layui-form" action="" method="post" lay-filter="example">
    <blockquote class="site-text layui-elem-quote" style="margin:0;margin-top:-15px;padding: 10px;border-left: 5px solid #009688">
        编辑优惠券
    </blockquote>
    <table class="layui-table" style="margin: 0;">
        <tr>
            <td width="15%">优惠券名称</td>
            <td colspan="3">
                <input type="hidden" name="id" value="">
                <input type="text" name="title" lay-verify="required" maxlength="30" autocomplete="off" placeholder="请输入优惠券名称" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="15%">优惠券类型</td>
            <td>
                <input type="radio" name="coupon_type" value="1" title="新人券" checked="checked">
                <input type="radio" name="coupon_type" value="2" title="促销券">
                <input type="radio" name="coupon_type" value="3" title="分享券">
            </td>
            <td width="15%">使用限制</td>
            <td>
                <input type="radio" name="sales_rule_scope" value="1" lay-filter="sales_rule_scope" title="全场通用" checked="checked">
                <input type="radio" name="sales_rule_scope" value="2" lay-filter="sales_rule_scope" title="部分商品可用">
            </td>
        </tr>
        <tr>
            <td width="10%">优惠券面额(元)</td>
            <td>
                <input type="text" name="discount_amount" lay-verify="required|number" maxlength="10" autocomplete="off" placeholder="请输入优惠券面额，比如：67.56" class="layui-input">
            </td>
            <td width="10%">门槛金额(元)</td>
            <td>
                <input type="text" name="condition" lay-verify="required|number" maxlength="10" autocomplete="off" placeholder="门槛金额" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="10%">发放总量</td>
            <td>
                <input type="text" name="uses_per_coupon" lay-verify="required|number" maxlength="30" autocomplete="off" placeholder="1" class="layui-input">
            </td>
            <td width="10%">每人限制领取</td>
            <td>
                <input type="text" name="uses_per_customer" lay-verify="required|number" maxlength="5" autocomplete="off" placeholder="每人限制领取" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="10%">领取后失效时间(天)</td>
            <td>
                <input type="text" name="effective_day" lay-verify="required|number" maxlength="3" autocomplete="off" placeholder="请输入失效时间，比如：7" class="layui-input">
            </td>
            <td width="15%">是否与新人专区<br/>商品互斥
            </td>
            <td>
                <input type="radio" name="activity_exclude" value="1" title="是" checked="">
                <input type="radio" name="activity_exclude" value="2" title="否">
            </td>
        </tr>
        <tr>
            <td width="10%">
                备注(后台)
            </td>
            <td colspan="3">
                <textarea name="remark" placeholder="请输入内容" lay-verify="required" maxlength="100" class="layui-textarea"></textarea>
            </td>
        </tr>
        <tr id="goodstr" style="display:<?php echo $info['sales_rule_scope']==1?'none':'';?>;">
            <td width="10%">
                优惠券商品
            </td>
            <td colspan="3">
                <button id="chooseGoodBtn" type="button" class="layui-btn layui-btn-warm" lay-filter="chooseGoodBtn">
                    <i class="layui-icon">&#xe608;</i> 添加优惠券商品
                </button>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>商品ID</th>
                        <th>商品名称</th>
                        <th>供应商</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="goodbox">
                       <?php if ($goodList) { ?>
                            <?php foreach ($goodList as $good) { ?>
                               <tr id="prod_<?php echo $good['id'];?>"><td><?php echo $good['id'];?></td>
                                   <td><input type="hidden" name="prodids[]" value="<?php echo $good['id'];?>"><?php echo $good['name'];?></td>
                                   <td><?php echo $good['wholesaler_name'];?></td>
                                   <td><button class="layui-btn" onclick="delguige(this)">移除</button></td>
                               </tr>
                            <?php } ?>
                       <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr><td></td><td colspan="3"><button class="layui-btn" lay-submit="" lay-filter="submit">保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button></td></tr>
    </table>
</form>
<script src="../../layui/layui.js"></script>
<script>
    var good_url = "<?php echo \yii\helpers\Url::toRoute(['choose/good']);?>";
    function delguige(obj)
    {
        $(obj).parent().parent().remove();
    }
    function getGoods(data)
    {
        var dataList = eval(data);
        if (dataList) {
            var len = dataList.length;
            var html = '';
            for (var i=0; i<len;i++) {

                var curId = 'prod_' + dataList[i].id;
                if ($('#' + curId).size() > 0) {
                    continue;
                }
                html += '<tr id="'+curId+'"><td>'+dataList[i].id+'</td><td><input type="hidden" name="prodids[]" value="'+dataList[i].id+'">'+dataList[i].name+'</td><td>'+dataList[i].wholesaler_name+'</td><td><button class="layui-btn" onclick="delguige(this)">移除</button></td></tr>';
            }

            $("#goodbox").append(html);
        }
    }

    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        //表单初始赋值
        form.val('example', {
            "id": "<?php echo $info['id'];?>"
            ,"title": "<?php echo $info['title'];?>"
            ,"coupon_type": "<?php echo $info['coupon_type'];?>"
            ,"discount_amount": "<?php echo $info['discount_amount'];?>"
            ,"condition": "<?php echo $info['condition'];?>"
            ,"uses_per_coupon": "<?php echo $info['uses_per_coupon'];?>"
            ,"uses_per_customer": "<?php echo $info['uses_per_customer'];?>"
            ,"effective_day": "<?php echo $info['effective_day'];?>"
            ,"activity_exclude": "<?php echo $info['activity_exclude'];?>"
            ,"remark": "<?php echo $info['remark'];?>"
            ,"sales_rule_scope": "<?php echo $info['sales_rule_scope'];?>"
        });

        //使用条件
        form.on('radio(sales_rule_scope)', function(data){
            if (data.value == 1) {
                $("#goodstr").hide();
            } else {
                $("#goodstr").show();
            }
        });

        //监听提交
        form.on('submit(submit)', function(data){
            var url = "<?php echo \yii\helpers\Url::toRoute(['coupon/add']);?>";
            var param = data.field;

            //部分商品
            if (param.sales_rule_scope == 2) {
                var goodsLen = $('#goodbox').find('tr').size();
                if (goodsLen == 0) {
                    layer.msg('请添加优惠卷商品！', {icon: 5});
                    $('#chooseGoodBtn').focus();
                    return false;
                }
            }

            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        var index_url = "<?php echo \yii\helpers\Url::toRoute(['coupon/index']);?>";
                        layer.msg(res.message, {icon: 1});
                        setTimeout(function(){
                             window.location = index_url;
                        }, 2000);
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
                title:'选择优惠卷商品',
                area: ['70%', '90%'],
                content: good_url
            });
            return false;
        });
    });
</script>