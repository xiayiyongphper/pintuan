<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->title = '市场运营配置';
$this->params['breadcrumbs'][] = ['label' => '运营管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="../../layui/css/layui.css">
<form class="layui-form" action="" method="post" lay-filter="example">
    <blockquote class="site-text layui-elem-quote" style="margin:0;margin-top:-15px;padding: 10px;border-left: 5px solid #009688">
        市场运营配置
    </blockquote>
    <table class="layui-table" style="margin: 0;">
        <tr>
            <td width="15%">接龙成功文案</td>
            <td colspan="3">
                <input type="hidden" value="<?php echo $id; ?>" id="configure_id" name="configure_id">
                <input type="text" name="solitaire_success_msg" value="<?php echo $solitaire_success_msg; ?>" lay-verify="required" maxlength="100" autocomplete="off" placeholder="请输入接龙成功文案" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="15%">邀请按钮文案</td>
            <td colspan="3">
                <input type="text" name="invite_btn_msg" value="<?php echo $invite_btn_msg; ?>" lay-verify="required" maxlength="30" autocomplete="off" placeholder="请输入邀请按钮文案" class="layui-input">
            </td>
        </tr>
        <tr>
            <td width="15%">招募团长的banner图片</td>
            <td colspan="3">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="invite_colonel_banner_btn">上传招募团长banner</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="invite_colonel_banner_img" src="<?php echo $invite_colonel_banner; ?>">
                        <input type="hidden" id="invite_colonel_banner" name="invite_colonel_banner" value="<?php echo $invite_colonel_banner; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="16%">招募团长的详情图片</td>
            <td colspan="3">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="colonel_describe_img_btn">上传招募团长的详情</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="colonel_describe_img_display" src="<?php echo $colonel_describe_img; ?>">
                        <input type="hidden" id="colonel_describe_img" name="colonel_describe_img" value="<?php echo $colonel_describe_img; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="15%">客服二维码</td>
            <td colspan="3">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="custom_qrcode_btn">上传客服二维码</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="custom_qrcode_display" src="<?php echo $custom_qrcode; ?>">
                        <input type="hidden" id="custom_qrcode" name="custom_qrcode" value="<?php echo $custom_qrcode; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="15%">客服微信号</td>
            <td colspan="3">
                <input type="text" name="custom_nickname" value="<?php echo $custom_nickname; ?>" lay-verify="required" maxlength="20" autocomplete="off" placeholder="请输入客服昵称" class="layui-input">
            </td>
        </tr>
        <tr><td></td><td colspan="3"><button class="layui-btn" lay-submit="" lay-filter="submit">保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button></td></tr>
    </table>
</form>
<script src="../../layui/layui.js"></script>
<script>
    var uploadUrl = "<?php echo \yii\helpers\Url::toRoute(['marketconfigure/upload']);?>";

    layui.use(['form','upload'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,upload = layui.upload;

        //招募团长banner图片
        var uploadInst = upload.render({
            elem: '#invite_colonel_banner_btn'
            ,url: uploadUrl
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
                    $('#invite_colonel_banner_img').attr('src', res.data.src);
                    $('#invite_colonel_banner').val(res.data.src);
                    console.log(res.data.src);
                }
            }
            ,error: function(){
            }
        });


        //团长说明详情图片
        var uploadInst2 = upload.render({
            elem: '#colonel_describe_img_btn'
            ,url: uploadUrl
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    //$('').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 1){
                    return layer.msg(res.msg);
                } else {
                    //上传成功
                    $('#colonel_describe_img_display').attr('src', res.data.src);
                    $('#colonel_describe_img').val(res.data.src);
                }
            }
            ,error: function(){
            }
        });

        //客服二维码
        var uploadInst3 = upload.render({
            elem: '#custom_qrcode_btn'
            ,url: uploadUrl
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    //$('').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 1){
                    return layer.msg(res.msg);
                } else {
                    //上传成功
                    $('#custom_qrcode_display').attr('src', res.data.src);
                    $('#custom_qrcode').val(res.data.src);
                }
            }
            ,error: function(){
            }
        });


        //监听提交
        form.on('submit(submit)', function(data){
            var url = "<?php echo \yii\helpers\Url::toRoute(['marketconfigure/add']);?>";
            var param = data.field;

            $.ajax({
                type: 'post',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        var index_url = "<?php echo \yii\helpers\Url::toRoute(['marketconfigure/index']);?>";
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
    });
</script>