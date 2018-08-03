<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;
AppAsset::register($this);
?>
<style>
    .mybtn {
        color: #fff;
        background-color: #398439;
        border-color: #255625;
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
</style>
<script src="./../laydate/laydate.js"></script>
<form id="myform" action="export" method="get">
<table class="table table-bordered">
    <tr class="warning"><td colspan="2" style="color:blue;font-size: 16px;">
            温馨提示
        </td></tr>
    <tr class="success"><td colspan="2" style="color:blue;font-size: 16px;">1、导出订单单据(配送单、详情单、总数单)<br/>2、导出规则：【已支付、已发货、已到货 + 尚未删除】的订单<br/>3、导出过程中，请您耐心等待！若数据过大无法导出，请按单个供货商导出！</td></tr>
<!--    <tr><td width="20%">单据类型</td>-->
<!--         <td>-->
<!--             <label><input type="radio" name="datatype" value="1" checked>全部</label>-->
<!--             <label><input type="radio" name="datatype" value="2">配送单</label>-->
<!--             <label><input type="radio" name="datatype" value="3">详情单</label>-->
<!--             <label><input type="radio" name="datatype" value="4">总数单</label>-->
<!--        </td></tr>-->
<!--    <tr><td width="20%">下单类型</td>-->
<!--        <td>-->
<!--            <label><input type="radio" name="ordertype" value="1" checked>全部</label>-->
<!--            <label><input type="radio" name="ordertype" value="2">普通</label>-->
<!--            <label><input type="radio" name="ordertype" value="3">拼团</label>-->
<!--        </td></tr>-->
    <tr><td width="20%">供货商</td><td>
                <?php if ($wholesalerList) { ?>
                    <?php foreach ($wholesalerList as $key=>$val) { ?>
                        <label><input type="checkbox" name="wholesaler_id[]" value="<?php echo $val['id'];?>" checked><?php echo $val['name'];?></label>
                    <?php } ?>
                <?php } ?>
            <input type="hidden" value="" name="wholesaler_name" id="wholesaler_name">
        </td></tr>
    <tr>
        <td width="20%">
            <label><input type="radio" name="time_type" value="1" checked>发货时间</label>
            <label><input type="radio" name="time_type" value="2">支付时间</label>
        </td>
        <td>
            <input type="text" name="startday" id="startday" placeholder="起始时间" autocomplete="off">至<input type="text" placeholder="截止时间" id="endday" name="endday" autocomplete="off">
        </td>
    </tr>
    <tr><td colspan="2" align="center"><input type="button" class="mybtn" id="submit" value="导出"></td></tr>
</table>
</form>
<?php
$url = \yii\helpers\Url::toRoute('/orderexport/export');
?>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script>
    var today = "<?php echo $todayDate;?>";
    var yestodayDate = "<?php echo $yestodayDate;?>";
    $(function(){
        laydate.render({
            elem: '#startday'
            ,type: 'datetime'
            ,value: yestodayDate + ' 22:00:00'
        });
        laydate.render({
            elem: '#endday'
            ,type: 'datetime'
            ,value: today+ ' 22:00:00'
        });

        $("#submit").click(function(){
                var names = '';
                $("input[type='checkbox']:checked").each(function(index){
                    names += $(this).parent().text();
                    names += ",";
                });

                if (names == '') {
                     alert('请选择供货商！');
                     return;
                }
               $("#wholesaler_name").val(names);

                if (!$("#startday").val()) {
                    alert('请选择开始时间！');
                    return;
                }

                if (!$("#endday").val()) {
                    alert('请选择结束时间！');
                    return;
                }

                var data = $("#myform").serialize();
                var url = "<?php echo $url;?>?" + data;
                window.location = url;
       });
    });
</script>