<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
 */
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
    'id'=>'author-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
    <link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
            <!--作者名称-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name_author', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'name_author',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <!-- 头像图片 -->
            <div class="form-group" >
                <?php echo $form->labelEx($model, 'head_img', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div style="height:150px;width:400px;">
                        <img id="head_img_show" src="<?php echo isset($model->head_img)?$model->head_img:'';?>" width="150" height="150" />
                    </div>
                    <div class="col-xs-10">
                        <div id="head_img_upload">
                        </div>
                        <div id="news_photo_upload_queue" style="padding: 3px;">
                        </div>
                        <input id="head_img" name="Author[head_img]" type="hidden" value="<?php echo isset($model->head_img)?$model->head_img:'';?>">
                    </div>
                </div>
            </div>
            <!-- 二维码图片 -->
            <div class="form-group" >
                <?php echo $form->labelEx($model, 'qr_img', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div style="height:150px;width:400px;">
                        <img id="qr_img_show" src="<?php echo isset($model->qr_img)?$model->qr_img:'';?>" width="150" height="150" />
                    </div>
                    <div class="col-xs-10">
                        <div id="qr_img_upload">
                        </div>
                        <div id="news_photo_upload_queue" style="padding: 3px;">
                        </div>
                        <input id="qr_img" name="Author[qr_img]" type="hidden" value="<?php echo isset($model->qr_img)?$model->qr_img:'';?>">
                    </div>
                </div>
            </div>
            <!--作者简介-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'summary',array('size'=>60,'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
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
        $(function(){
            //头像
            $("#head_img_upload").uploadify({
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
                        $("#head_img").val(path);
                        $("#head_img_show").attr('src',path);
                    }else{
                        alert('图片上传失败')
                    }
                }
            });
            //分享图上传
            $("#qr_img_upload").uploadify({
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
                        $("#qr_img").val(path);
                        $("#qr_img_show").attr('src',path);
                    }else{
                        alert('图片上传失败')
                    }
                }
            });
        })
    </script>
<?php $this->endWidget(); ?>