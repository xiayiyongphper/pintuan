<?php
use \yii\helpers\Url;

/* @var $this yii\web\View */
?>

<div class="row relative">
    <div class="controls">
        <input id="keyword" type="text" style="margin-left: 10px;height: 30px"/>
        <input id="search" type="button" value="查询" style="margin-left: 10px;height: 30px" onclick="search()"/>
    </div>
</div>

<div class="row relative" style="margin-top: 10px">
    <table class="table table-bordered" id="list" >
    </table>
</div>