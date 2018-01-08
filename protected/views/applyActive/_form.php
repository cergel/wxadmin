<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:'YYYY-MM-DD HH:mm'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    var isnew = '" . $model->isNewRecord . "';

    $('#ApplyActive_is_remark').click(function () {
        var is_remark = $('#ApplyActive_is_remark:checkbox:checked').val();
        if(is_remark == 1){
            $('#ApplyActive_remark').removeAttr('disabled');
        }else{
            $('#ApplyActive_remark').attr('disabled','disabled');
        }
    });
    if(!isnew){
        var is_remark = $('#ApplyActive_is_remark:checkbox:checked').val();
        if(is_remark==1){
            $('#ApplyActive_remark').removeAttr('disabled');
        }else{
            $('#ApplyActive_remark').attr('disabled','disabled');
        }
    }

    if(isnew){
        $('#ApplyActive_remark').attr('disabled','disabled');
        // 选中生成页面平台
        checkedAll($('#ApplyActive_platform :checkbox'));
        // 选中App分享平台
        checkedAll($('#ApplyActive_share :checkbox'));
    }

    // 选中复选框所有选项
    function checkedAll(checkBoxs) {
        for(var i =0 ; i < checkBoxs.length ; i++) {
            checkBoxs.attr('checked' , 'checked');
        }
    }

    selectPlatform();
    //选中ios ，android平台，不允许修改
    function selectPlatform() {
        var platform = $('#ApplyActive_platform :checkbox');
        for(var i =0 ; i < platform.length ; i++) {
            var value = platform[i].value;
            if(value == '8' || value == '9' ) {
                platform[i].onclick=function(){
                    this.checked = 'checked';
                    return false;
                };
            }
        }
    }

     function validate(val) {
        if(val == '') {
            alert('未输入价格');
            return false;
        }
        var pattern = /^[1-9]\d{0,4}(\.\d{1,2}){0,1}$/;
        var flag = pattern.test(val.trim());
        if(!flag) {
            var pattern = /^0(\.\d{1,2}){0,1}$/;
            flag = pattern.test(val.trim());
            if(!flag) {
                alert('错误，输入价格只能为0-99999.99');
            }
        }
        return flag;
    }

   $('#submit').click(function(){
        var index = $('#ApplyActive_p_type :radio:checked').val();
        var start_display=$('#ApplyActive_start_display').val().replace(/-/g,'/');
        var end_display=$('#ApplyActive_end_display').val().replace(/-/g,'/');
        if(!compare(start_display,end_display)){
            return false;
        };
        var start_apply=$('#ApplyActive_start_apply').val().replace(/-/g,'/');
        var end_apply=$('#ApplyActive_end_apply').val().replace(/-/g,'/');
        if(!compare(start_apply,end_apply)){
            return false;
        };
        if(index == 1) {
              //固定价格的输入框
            var stable_price = document.getElementById('stable_price').getElementsByTagName('input')[0];
            return validate(stable_price.value);
        }else if(index == 2){
            //价格区间，是数组
            var range_price = document.getElementById('range_price').getElementsByTagName('input');
            for(var i = 0 ; i < range_price.length; i++) {
                if(!validate(range_price[i].value)) {
                    return false;
                }
            }
            if(eval(range_price[0].value) >= eval(range_price[1].value)){
                alert('最低价大于或等于最高价！！！');
                return false;
            }
        }
   });
   function compare(start,end){
        var flag2=false;
        var start = Date.parse(new Date(start));
        start = start / 1000;
        var end = Date.parse(new Date(end));
        end = end / 1000;
        if(start > end){
          alert('开始时间大于结束时间！！！');
          return false;
        }
        return true;
   }


");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'apply-active-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <!--活动分类-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model, 'type', ApplyActive::model()->getAllType(), array('separator' => ' ')); ?>
            </div>
        </div>
        <!--活动标题-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 25, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--图片-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'picture', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:150px;width:250px;">
                    <img id="picture_img" src="<?php echo isset($model->picture) ? $model->picture : ''; ?>" width="180"
                         height="130"/>
                    <input id="pictureUploadClean" type="button" value="删除">
                </div>
                <div class="col-xs-10">
                    <div style="color:#D42124;margin-bottom:10px;padding:0px">上传建议尺寸750X500px</div>
                    <div id="pictureUpload">
                    </div>
                    <div id="pictureUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="pictureInput" name="ApplyActive[picture]" type="hidden"
                           value="<?php echo isset($model->picture) ? $model->picture : ''; ?>">
                </div>
            </div>
        </div>
        <!--标签-->
        <div class="form-group">
            <?php echo CHtml::label('标签', '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <table width="100%">
                    <tr>
                        <td>
                            <?php echo $form->textField($model, 'tags', array('size' => 60, 'maxlength' => 15, 'class' => 'col-xs-10')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="color:#D42124">多个标签请用逗号隔开</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!--价格-->
        <div class="form-group">
            <?php echo CHtml::label('价格', '', array('required' => 'true', 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <table width="100%" border="0">
                    <tr height="30px">
                        <td width="10%"
                            rowspan="3"><?php echo $form->radioButtonList($model, 'p_type', array('0' => '免费', '1' => '固定价格', '2' => '价格区间')); ?></td>
                        <td width="20%">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr height="30px" id="stable_price">
                        <td><?php
                            if (isset($model->price)) {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-12', 'value' => $model->price[0]));
                            } else {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-12'));
                            }
                            ?></td>
                        <td>&nbsp;元</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="range_price">
                        <td><?php if (isset($model->price)) {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-12', 'value' => $model->price[1]));
                            } else {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-12'));
                            } ?></td>
                        <td>&nbsp;&nbsp;---</td>
                        <td><?php
                            if (isset($model->price)) {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-3', 'value' => $model->price[2]));
                            } else {
                                echo $form->textField($model, 'price[]', array('size' => 60, 'class' => 'col-xs-3'));
                            }
                            ?><p style="margin-top: 5px">元</p></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--活动开始展示时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_display', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'start_display', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--活动结束展示时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_display', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'end_display', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--活动地点-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'address', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'address', array('size' => 60,'maxlength'=>30, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--报名设置-->
        <div class="form-group">
            <?php echo CHtml::label('报名设置', '', array('required' => 'true', 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php
                $model->is_form=1;
                echo $form->radioButtonList($model, 'is_form', array('0' => '无需填写表单', '1' => '需要填写表单')); ?>
                <table width="100%" border="0">
                    <tr>
                        <td width="13%">&nbsp;&nbsp;&nbsp;&nbsp;<?php
                            if ($model->isNewRecord) {
                                $model->is_remark = 0;
                                echo $form->checkBox($model, 'is_remark', array('value' => '1'));
                            } else {
                                echo $form->checkBox($model, 'is_remark', array('value' => '1'));
                            }
                            ?><label>是否显示备注</label></td>
                        <td><?php
                            if ($model->is_remark == 0) {
                                echo $form->textField($model, 'remark', array('size' => 60, 'maxlength' => 4, 'class' => 'col-xs-4', 'value' => '备注'));
                            } else {
                                echo $form->textField($model, 'remark', array('size' => 60, 'maxlength' => 4, 'class' => 'col-xs-4'));
                            }
                            ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--答题-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'question', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'question', array('size' => 10,'maxlength'=>10, 'class' => 'col-xs-60')); ?>
            </div>
        </div>
        <!--活动详情-->
        <div id="type_1" class="type_div"
        ">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'detail', array('id' => '', 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <!-- 加载编辑器的容器 -->
                <script id="container" name="ApplyActive[detail]" type="text/plain" style="width:640px; height:350px;">
                    <?php echo isset($model->detail) ? $model->detail : ''; ?>
                </script>
                <!-- 配置文件 -->
                <script type="text/javascript" src="/assets/js/ueditor/ueditor.config.js"></script>
                <!-- 编辑器源码文件 -->
                <script type="text/javascript" src="/assets/js/ueditor/ueditor.all.js"></script>
                <!-- 实例化编辑器 -->
                <script type="text/javascript">
                    var ue = UE.getEditor('container');
                </script>
                <div style="color:#D42124;margin-top:8px;" >增加影片 card 在编辑器中粘贴{movieId:5853},请不要带任何格式</div>
            </div>
        </div>
    </div>
    <!--活动报名开始时间-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'start_apply', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <div class="input-group col-xs-3">
                <?php echo $form->textField($model, 'start_apply', array('class' => 'form-control date-timepicker')); ?>
                <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
            </div>
        </div>
    </div>
    <!--活动报名结束时间-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'end_apply', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <div class="input-group col-xs-3">
                <?php echo $form->textField($model, 'end_apply', array('class' => 'form-control date-timepicker')); ?>
                <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
            </div>
        </div>
    </div>
    <!--参加注水-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'support', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php
            if ($model->isNewRecord){
                $model->support=0;
                echo $form->textField($model, 'support', array('size' => 60, 'maxlength' => 5,'class' => 'col-xs-10'));
            }else{
                echo $form->textField($model, 'support', array('size' => 60, 'maxlength' => 5,'class' => 'col-xs-10'));
            }
            ?>
        </div>
    </div>
    <!--生成页面平台-->
    <div class="form-group">
        <?php echo CHtml::label('生成页面平台', '', array('required' => true, 'class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <div class="col-xs-10">
                <?php echo $form->checkBoxList($model, 'platform', $model->getPlatformKey(), array('separator' => '  ')); ?>
            </div>
        </div>
    </div>
    <!--分享图标-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_icon', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <div style="height:150px;width:250px;">
                <img id="share_icon_img" src="<?php echo isset($model->share_icon) ? $model->share_icon : ''; ?>"
                     width="180" height="130"/>
                <input id="share_iconUploadClean" type="button" value="删除">
            </div>
            <div class="col-xs-10">
                <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于 32K</div>
                <div id="share_iconUpload">
                </div>
                <div id="share_iconUploadQueue" style="padding: 3px;">
                </div>
                <input id="share_iconInput" name="ApplyActive[share_icon]" type="hidden"
                       value="<?php echo isset($model->share_icon) ? $model->share_icon : ''; ?>">
            </div>
        </div>
    </div>
    <!--分享标题-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model, 'share_title', array('size' => 60, 'maxlength' => 15, 'class' => 'col-xs-10')); ?>
        </div>
    </div>
    <!--分享内容-->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_describe', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model, 'share_describe', array('size' => 60,'maxlength' => 30, 'class' => 'col-xs-10')); ?>
        </div>
    </div>

    <!-- APP分享平台 -->
    <div class="form-group">
        <?php echo CHtml::label('APP分享平台', '', array('required' => true, 'class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <div class="col-xs-10">
                <?php echo $form->checkBoxList($model, 'share', $model->getShareKey(), array('separator' => ' ')); ?>
            </div>
        </div>
    </div>


    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="submit"id="submit">
                <i class="ace-icon fa fa-check bigger-110"></i>
                <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
            </button>
            &nbsp; &nbsp; &nbsp;
            <button class="btn" type="reset">
                <i class="ace-icon fa fa-undo bigger-110"></i>
                重置
            </button>
        </div>
    </div>
</div>
</div>
<script>
    $(function () {
        //图片上传
        $("#pictureUpload").uploadify({
            //开启调试
            'debug': false,
            //是否自动上传
            'auto': true,
            'buttonText': '上传',
            'multi': true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID': 'pictureUploadQueue',
            'fileObjName': 'UpLoadFile',
            'uploader': '/applyActive/ajaxUpload',
            'multi': false,//禁止批量上传，要一次一次的传
            'fileTypeDesc': '支持的格式：',
            'fileTypeExts': '*',
            'fileSizeLimit': '10MB',
            'removeTimeout': 1,
            //检测FLASH失败调用
            'onFallback': function () {
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess': function (file, data, response) {
                jsonObj = jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if (succ == 1) {
                    $('#pictureInput').val(path);
                    $("#picture_img").attr('src', path);
                } else {
                    alert('图片上传失败')
                }
            }
        });

        //分享图片 删除
        $("#pictureUploadClean").click(function () {
            $('#pictureInput').val('');
            $("#picture_img").attr('src', '');
        })
        $("#share_iconUpload").uploadify({
            //开启调试
            'debug': false,
            //是否自动上传
            'auto': true,
            'buttonText': '上传',
            'multi': true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID': 'share_iconUploadQueue',
            'fileObjName': 'UpLoadFile',
            'uploader': '/applyActive/ajaxUpload',
            'multi': false,//禁止批量上传，要一次一次的传
            'fileTypeDesc': '支持的格式：',
            'fileTypeExts': '*',
            'fileSizeLimit': '10MB',
            'removeTimeout': 1,
            //检测FLASH失败调用
            'onFallback': function () {
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess': function (file, data, response) {
                jsonObj = jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if (succ == 1) {
                    $('#share_iconInput').val(path);
                    $("#share_icon_img").attr('src', path);
                } else {
                    alert('图片上传失败')
                }
            }
        });

        //分享图片 删除
        $("#share_iconUploadClean").click(function () {
            $('#share_iconInput').val('');
            $("#share_icon_img").attr('src', '');
        })

    })

</script>
<?php $this->endWidget(); ?>
