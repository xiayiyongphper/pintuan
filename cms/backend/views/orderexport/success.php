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
<table class="table table-bordered">
    <tr class="success"><td colspan="2" style="color:blue;font-size: 16px;">导出结果如下：</td></tr>
    <tr><td width="20%">您选择的供货商</td>
        <td>
             <?php echo $name;?>
        </td>
    </tr>
    <tr><td width="20%">您选择的查询时间</td>
        <td>
            起始时间：<?php echo $start;?>------截止时间：<?php echo $end;?>
        </td>
    </tr>
    <tr><td width="20%">配送单下载</td>
        <td>
            <a href="./../orderexcel/<?php echo $fileName; ?>.xlsx"><?php echo $fileName . '.xlsx';?></a>
        </td>
    </tr>
    <tr><td width="20%">详情单下载</td>
        <td>
            <a href="./../orderexcel/<?php echo $fileName2; ?>.xlsx"><?php echo $fileName2 . '.xlsx';?></a>
        </td>
    </tr>
    <tr><td width="20%">总数单下载</td>
        <td>
            <a href="./../orderexcel/<?php echo $fileName3; ?>.xlsx"><?php echo $fileName3 . '.xlsx';?></a>
        </td>
    </tr>
</table>