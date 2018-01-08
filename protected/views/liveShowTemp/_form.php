<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'live-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!-- 标题 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10','do'=>'notnull')); ?>
            </div>
        </div>
        <!-- 图片 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'img', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="img_news" src="<?php echo isset($model->img)?$model->img:'';?>" width="180" height="130" />
                </div>
                <div class="col-xs-10">
                    <div id="img_file_upload">
                    </div>
                    <div id="news_photo_upload_queue" style="padding: 3px;">
                    </div>
                    <input id="img_file" name="LiveShowTemp[img]" type="hidden" value="<?php echo isset($model->img)?$model->img:'';?>">
                </div>
            </div>
        </div>
        <!-- 人物 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'actor', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'actor',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 时间 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>

        <div class="form-group">
        <?php echo $form->labelEx($model, 'end_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <div class="input-group col-xs-3">
                <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker')); ?>
                <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
            </div>
        </div>
    </div>
        <!-- 文案 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'text_2', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'text_2',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 文案 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'text_3', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'text_3',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 文案 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'text_4', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'text_4',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'temp_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'temp_title',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'temp_url', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'temp_url',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 下载按钮链接 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'down_href', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'down_href',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 分享标题 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'share_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_title',array('size'=>60,'maxlength'=>30,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 分享描述 -->
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'share_summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_summary',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 分享图片 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'share_img', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="share_img_new" src="<?php echo isset($model->share_img)?$model->share_img:'';?>" width="180" height="130" />
                </div>
                <div class="col-xs-10">
                    <div id="share_file_upload">
                    </div>
                    <div id="share_img_queue" style="padding: 3px;">
                    </div>
                    <input id="share_img" name="LiveShowTemp[share_img]" type="hidden" value="<?php echo isset($model->share_img)?$model->share_img:'';?>">
                </div>
            </div>
        </div>
        <!-- 样式 -->
        <div class="form-group">
            <?php echo $form->labelEx($model,'css', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textArea($model,'css',array('rows' => 5, 'class' => 'col-xs-10', 'placeholder' => '')); ?>
            </div>
        </div>


        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" do="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset" onclick="window.location.reload(true);">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        //直播头图
        $("#img_file_upload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传图片',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'coverUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/img/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    $("#img_file").val(path);
                    $("#img_news").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });
        //分享图上传
        $("#share_file_upload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'sourceHeadUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/img/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    $("#share_img").val(path);
                    $("#share_img_new").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });
//        $("[do='submit']").click(function(){
//            //该死的浏览器兼容性
//            var returnInfo =true;
//            $("[do=notnull]").each(function(){
//
//                if($(this).val() == ''){
//                    alert('请检查必填数据');
//                    $(this).focus();
//                    returnInfo = false;
//                    return returnInfo;
//                }
//            });
//            return returnInfo;
//        });

    })
</script>
    <?php $this->endWidget(); ?>
