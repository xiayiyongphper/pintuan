<?php

use yii\helpers\Url;

?>
<link href="/../css/common.css" type="text/css" rel="stylesheet">
<link href="/../css/add.css" type="text/css" rel="stylesheet">
<div class="row relative">
    <a href="<?php echo Url::toRoute(['wholesaler/detail', 'id' => $model['id']]) ?>">基础信息</a>
    <label style="margin-left: 20px">乐小拼信息</label>
    <div class="addwrap">
        <div class="addl fl">
            <form id="myform">
                <div class="additem">
                    <label>结算周期(天)</label>
                    <input type="text" placeholder="" name="Wholesaler[settlement_cycle]"
                           value="<?php echo $model['settlement_cycle']; ?>">
                </div>
                <div class="additem">
                    <label>保证金(元)</label>
                    <input type="text" placeholder="" name="Wholesaler[margin]"
                           value="<?php echo $model['margin'] / 100; ?>">
                </div>
                <div class="additem">
                    <label>开户银行</label>
                    <input type="text" name="Wholesaler[bank]" value="<?php echo $model['bank']; ?>">
                </div>
                <div class="additem">
                    <label>开户名称</label>
                    <input type="text" name="Wholesaler[account_name]" value="<?php echo $model['account_name']; ?>">
                </div>
                <div class="additem">
                    <label>开户账号</label>
                    <input type="text" name="Wholesaler[account]" value="<?php echo $model['account']; ?>">
                </div>
                <div class="additem">
                    <label>是否开业</label>
                    <input type="radio" name="Wholesaler[status]" <?php if (1 == $model['status']) echo 'checked' ?>
                           value='1'/><label style="width: 30px">开业</label>
                    <input type="radio" name="Wholesaler[status]" <?php if (2 == $model['status']) echo 'checked' ?>
                           value="2"/><label style="width: 30px">停业</label>
                </div>
                <input type="hidden" name="id" value="<?php echo $model['id']; ?>">
                <div class="additem" style="margin-top: 20px;">
                    <button style="width: 200px" type="button" id="submitBtn">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(function () {
        $("#submitBtn").click(function () {
            var url = "<?php echo Url::toRoute(['/wholesaler/pin-update']); ?>";
            var data = $('#myform').serialize();
            $.post(url,
                data,
                function (data, status) {
                    alert(data.msg);
                },
                'json');
        });
    });
</script>
