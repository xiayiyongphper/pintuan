<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use \yii\redactor\widgets\Redactor;
use backend\assets\AppAsset;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.table-input.el-input {
width: 180px;
}
</style>

<div class="product-form">

    <?php $form = ActiveForm::begin(['id' => 'formId']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php $wholesaler_url = \yii\helpers\Url::toRoute(['wholesaler/select-list'])?>
    <?= $form->field($model, 'wholesaler_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => '请选择 ...'],
        'pluginOptions' => [
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => $wholesaler_url,
                'dataType' => 'json',
                'delay' => 350,
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                'results' => new JsExpression('function(data, params) {console.log(data); return {results: data.results}; }'),
            ],
        ]
    ])->label('供应商'); ?>

    <?= $form->field($model, 'status')->radioList(['1' => '上架',2 => '下架']) ?>

    <?= $form->field($model, 'sort') ?>
    <?= $form->field($model, 'unit') ?>

    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，商品图片的规格为：750*750 !</label>
    </div>
    <label class="control-label" for="category-name">商品图片</label>
    <div class="form-group">
      <?= Html::activeHiddenInput($model, 'images', ['id' => 'images']); ?>
        <?= FileInput::widget([
            'name' => 'Product[image]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/product/image-upload']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' => $p1,
                'initialPreviewConfig' => $p2,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#images").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                if(urlArray.length>0){
                                    urlArray.push(url);
                                }else{
                                    urlArray.push(url);
                                }
                            }else{
                                urlArray.push(url);
                            }
                            jQuery("#images").val(urlArray.join(";"));
                            console.log(jQuery("#images").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#images").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#images").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#images").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <label class="control-label" style="color:red;font-size:16px;">请看清楚，商品详情的规格为：750*任意 !</label>
    </div>
    <label class="control-label" for="category-name">商品详情</label>
    <div class="form-group">
        <?= Html::activeHiddenInput($model, 'description', ['id' => 'description']); ?>
        <?= FileInput::widget([
            'name' => 'Product[image]',
            'options' => [
                'multiple' => true
            ],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/product/image-upload2']),
                'showUpload' => false, // hide upload button
                'uploadAsync' => true,
                'initialPreview' =>$p3,
                'initialPreviewConfig' =>$p4,
                'initialPreviewAsData' => true,
                'overwriteInitial' => false,
                'maxFileSize' => 2800,
            ],
            'pluginEvents' => [
                'fileuploaded' => 'function(event, data, previewId, index){
                console.log(data);
                            var url = data.response.files[0].url;
                            var urls = jQuery("#description").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                if(urlArray.length>0){
                                    urlArray.push(url);
                                }else{
                                    urlArray.push(url);
                                }
                            }else{
                                urlArray.push(url);
                            }
                            jQuery("#description").val(urlArray.join(";"));
                            console.log(jQuery("#description").val());
                            console.log(\'File uploaded triggered\');
                        }',
                'filesorted' => 'function(event, params){
                            if(params.stack&&params.stack.length>0){
                                jQuery("#description").val(params.stack.join(";"));
                            }
                        }',
                'filedeleted' => 'function(event, key){
                            var urls = jQuery("#description").val();
                            var urlArray = [];
                            if(urls.length>0){
                                urlArray = urls.split(";");
                                var index = urlArray.indexOf(key);  
                                if (index > -1) {
                                    urlArray.splice(index, 1);  
                                }
                                jQuery("#description").val(urlArray.join(";"));
                            }
                            //console.log(urlArray);
                        }',
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <label class="control-label">商品分类</label>
        <select id="parent_id" class="form-control" style="width:30%;display: inline-block">
        </select>
        <select id="sencond_id" class="form-control" style="width:30%;display: inline-block">
            <option value="0">请选择</option>
        </select>
        <select name="Product[third_category_id]" id="third_category_id" class="form-control" style="width:30%;display: inline-block">
            <option value="0">请选择</option>
        </select>
    </div>
    <div id="app"></div>
<!--    <div class="form-group">-->
<!--        <?//= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>-->
<!--    </div>-->

    <?php ActiveForm::end(); ?>

    <button id="saveBtn">save</button>

    <script type="text/x-template" id="table">
        <div>
            <el-table
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        width="80">
                    <template slot-scope="scope">
                        <el-button type="danger" icon="el-icon-minus" size="mini" @click="deleteHandle(scope.$index)"></el-button>
                    </template>
                </el-table-column>
                <el-table-column
                        label="规格名称"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="请输入规格名称"
                                v-model="scope.row.k">
                        </el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="规格值"
                        min-width="180">
                    <template slot-scope="scope">
                        <el-input
                                class="table-input"
                                placeholder="请输入规格值"
                                v-model="scope.row.v[index]"
                                v-for="item, index in scope.row.v"
                                :key="index">
                            <i
                                    class="el-icon-close el-input__icon"
                                    slot="suffix"
                                    @click="subDeleteHandle(scope.row.v, index)">
                            </i>
                        </el-input>
                        <el-button type="danger" icon="el-icon-plus" size="mini" @click="subAddHandle(scope.row.v)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="padding: 10px 0 20px;">
                <el-button type="danger" icon="el-icon-plus" @click="addHandle">添加规格</el-button>
            </div>
            <el-table
                    :data="tableData"
                    border
                    style="width: 100%">
                <el-table-column
                        type="index"
                        label="序号"
                        width="50">
                </el-table-column>
                <el-table-column
                        :prop="col.k"
                        :label="col.v"
                        width="180"
                        v-for="col, index in cols"
                        :key="index">
                </el-table-column>
<!--                <el-table-column-->
<!--                        prop="code"-->
<!--                        label="商品编码"-->
<!--                        width="180">-->
<!--                </el-table-column>-->
                <el-table-column
                        label="进货价(元)"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="进货价"
                                v-model="scope.row.purchase_price">
                        </el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="自提佣金(元)"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="自提佣金"
                                v-model="scope.row.pick_commission">
                        </el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="推广佣金(元)"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="推广佣金"
                                v-model="scope.row.promote_commission">
                        </el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="销售价(元)"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="销售价"
                                v-model="scope.row.price">
                        </el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="库存"
                        width="180">
                    <template slot-scope="scope">
                        <el-input
                                placeholder="库存"
                                v-model="scope.row.qty">
                        </el-input>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script>
        // 深拷贝
        if (typeof Object.assign != 'function') {
            // Must be writable: true, enumerable: false, configurable: true
            Object.defineProperty(Object, "assign", {
                value: function assign(target, varArgs) { // .length of function is 2
                    'use strict';
                    if (target == null) { // TypeError if undefined or null
                        throw new TypeError('Cannot convert undefined or null to object');
                    }

                    var to = Object(target);

                    for (var index = 1; index < arguments.length; index++) {
                        var nextSource = arguments[index];

                        if (nextSource != null) { // Skip over if undefined or null
                            for (var nextKey in nextSource) {
                                // Avoid bugs when hasOwnProperty is shadowed
                                if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                                    to[nextKey] = nextSource[nextKey];
                                }
                            }
                        }
                    }
                    return to;
                },
                writable: true,
                configurable: true
            });
        }
        window.onload = function() {
            window.vm = new Vue({
                el: '#app',
                data: {
                    list: [],
                    cols: [],
                    tableData: []
                },
                watch: {
                    list: {
                        handler: function(val) {
                            var arr = []
                            var cols = []
                            val.forEach(function(item, index) {
                                if (item.k !== '' && item.v.length) {
                                    cols.push({
                                        k: 'k' + index,
                                        v: item.k
                                    })
                                    arr.push(item)
                                }
                            })
                            var list = this.recursive(arr)
//                            list.forEach(function(item) {
//                                Object.assign(item, {
//                                    price: '',
//                                })
//                            })
                            this.tableData = list
                            this.cols = cols
                            console.log(this.tableData)
                        },
                        deep: true
                    }
                },
                methods: {
                    recursive: function(arr) {
                        if (!arr.length) return []
                        var val = arr.pop() || {}
                        var len = arr.length
                        var current = val.v || []
                        var result = []
                        var res = this.recursive(arr)
                        if (!res.length) {
                            for (var i = 0; i < current.length; i++) {
                                var item = {}
                                item['k' + len] = current[i]
                                result.push(item)
                            }
                        } else {
                            for (var j = 0; j < res.length; j++) {
                                for (var i = 0; i < current.length; i++) {
                                    var item = Object.assign({}, res[j])
                                    item['k' + len] = current[i]
                                    result.push(item)
                                }
                            }
                        }
                        return result
                    },
                    addHandle: function() {
                        this.list.push({
                            k: '',
                            v: []
                        })
                    },
                    deleteHandle: function(index) {
                        this.list.splice(index, 1)
                    },
                    saveHandle: function() {
                        console.log(this.list)
                    },
                    subAddHandle: function(column) {
                        column.push('')
                    },
                    subDeleteHandle: function(column, index) {
                        column.splice(index, 1)
                    }
                },
                template: '#table'
            })

            $('#saveBtn').on('click', function() {
                //商品验证
                var proname = $('#product-name').val();
                if (!proname) {
                    alert('请填写商品名称！');
                    return;
                }

                var wholesaler_id = $('#product-wholesaler_id').val();
                if (!wholesaler_id) {
                    alert('请选择供应商！');
                    return;
                }

                var len = $("input[type='radio'][name*='status']:checked").size();
                if (len == 0) {
                    alert('请选择状态！');
                    return;
                }

                var sort = $('#product-sort').val();
                if (!sort) {
                    alert('请填写商品权重！');
                    return;
                }

                var unit = $("#product-unit").val();
                if (unit == '') {
                    alert('请填写商品单位！');
                    return;
                }

                if (!$("#images").val()) {
                    alert('请上传商品的图片！');
                    return;
                }

                if (!$("#description").val()) {
                    alert('请上传商品的详情图片！');
                    return;
                }

                if ($("#third_category_id").val() == 0) {
                    alert('请选择商品分类！');
                    return;
                }

                var len = $(".el-input__inner").size();
                if (len <=2) {
                    alert('请填写商品规格数据！');
                    return;
                }

                var formData = $('#formId').serializeArray();
                formData.push({
                    name: 'list',
                    value: JSON.stringify(vm.$data.list)
                })
                formData.push({
                    name: 'columns',
                    value: JSON.stringify(vm.$data.cols)
                })
                formData.push({
                    name: 'data',
                    value: JSON.stringify(vm.$data.tableData)
                })
                $.ajax({
                    url: "<?php echo \yii\helpers\Url::toRoute(['product/create'])?>",
                    method: 'post',
                    data: formData,
                    success: function(data) {
                        data = JSON.parse(data);
//                        console.log(data)
                        if(data.code != 0){
                            alert(data.msg)
                        }
                    }
                })
            })
        }

        //获取分类
        function get_category(id, curlevel, domId, curId) {
            var id = id || 0;
            var curlevel = curlevel || 1;
            var curId = curId || 0;

            var url = "<?php echo \yii\helpers\Url::toRoute(['category/catelist']) ?>";
            var param = {
                 id: id,
                level : curlevel
            };
            $.ajax({
                type: 'get',
                url: url,
                data: param,
                dataType: "json",
                success: function (res) {
                    fill_category(res, domId, curId);
                }
            });
        }
        //填充分类
        function fill_category(res, domId, curId) {
            var data = res.results;
            if (data) {
                var len = data.length;
                var html = '';
                var item = [];
                html += '<option value="0">请选择</option>';

                for (var i = 0; i < len; i++) {
                    item = data[i];
                    var selected = '';
                    if (curId == item.id) {
                        selected = ' selected';
                    }
                    html += '<option value="' + item.id + '"' + selected + '>' + item.name + '</option>';
                }
                $('#' + domId).html(html);
            }
        }

        $(function(){
            //初始化一级分类
            get_category(0, 1, 'parent_id');

            if ($("#parent_id").val() >0) {

            }
            //监听一级分类切换
            $("#parent_id").on('change', function () {
                if ( $(this).val() > 0) {
                    $('#third_category_id').html('<option value="0">选择区</option>');
                    get_category($(this).val(),2,'sencond_id');
                } else {
                    $('#third_category_id').html('<option value="0">请选择</option>');
                    $('#sencond_id').html('<option value="0">请选择</option>');
                }
            });
            //监听2级分类切换
            $("#sencond_id").on('change', function () {
                if ( $(this).val() > 0) {
                    get_category($(this).val(), 3, 'third_category_id');
                } else {
                    $('#third_category_id').html('<option value="0">请选择</option>');
                }
            });
        });
    </script>
</div>
