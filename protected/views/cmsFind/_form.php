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
    'id'=>'ad-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
<!--
    <div>
        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="submit"  style="margin-bottom: 20px;" id="submit">
                <i class="ace-icon fa fa-check bigger-110"></i>
                <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
            </button>
        </div>
    </div>
    -->
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!-- 类型 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'f_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->dropDownList($model,'f_type',ActiveFind::model()->getTypeList('list'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!-- 活动id-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if($model->isNewRecord){echo $form->textField($model,'a_id',array('id' => 'a_id','size'=>60,'maxlength'=>12,'class' => 'col-xs-10'));}
                else {echo $form->textField($model,'a_id',array('id' => 'a_id','size'=>60,'maxlength'=>12,'class' => 'col-xs-10','disabled'=>"disabled"));}  ?>
            </div>
        </div>
        <!-- 作者 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'f_writer', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'f_writer',array('id'=>'f_writer','size'=>60,'maxlength'=>30,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <!-- 标题 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'f_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'f_title',array('id'=>'sTitle','size'=>60,'maxlength'=>30,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <!-- 封面  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'f_cover', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="sCover_img" src="<?php echo isset($model->f_cover)?$model->f_cover:'';?>" width="180" height="130" />
                </div>
                <div class="col-xs-10">
                    <div id="type_1" class="type_div" >
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于 32K</div>
                    </div>
                    <div id="coverUpload">
                    </div>
                    <div id="coverUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="sCover" name="ActiveFind[f_cover]" type="hidden" value="<?php echo isset($model->f_cover)?$model->f_cover:'';?>">
                </div>
            </div>
        </div>
        <!-- 状态  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!-- 平台  -->
        <div class="form-group">
            <?php echo CHtml::label('平台', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'channel',ActiveCms::model()->getChannel('list'),array('separator'=>'  '));?>
                </div>
            </div>
        </div>
        <!--  跳转链接地址  -->
        <?php foreach(ActiveCms::model()->getChannel('list') as $key=>$list){  ?>
            <div class="form-group">
                <?php echo CHtml::label($list.'跳转链接', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php if(!empty($channelUrl[$key])){ ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value="<?php echo $channelUrl[$key];  ?>"  />
                    <?php }else{   ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value=""  />
                    <?php  } ?>
                </div>
            </div>

        <?php } ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'up_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'up_time',array('class' => 'form-control date-timepicker','do'=>"notnull")); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>


        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
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
        var picNum=0;

        //通过movieid获取影院名字
        $("#a_id").blur(function () {
            var a_id = $("#a_id").val();
            if(a_id == '')return false;
            $.ajax({
                data: "aid=" + a_id,
                url: "/cms/getCmsInfoAjax/"+a_id,
                type: "get",
                dataType: 'json',
                async: false,
                success: function (data) {
                    if(data){
                        for(var key in data){
                            $("#"+key).val(data[key]);
                        }
                        $("#sCover_img").attr('src',data['sCover']);
                    }else{
                        alert('不能使用的CMS内容');
                        $("#a_id").val('');
                    }
                },
                error: function ($data) {
                    alert("异常的活动id，请重新再试");
                    $("#a_id").val('');
                }
            });
        });

        $("#submit").click(function(){
            //该死的浏览器兼容性
            var returnInfo = true;
            //影片id
            var checboxValueInfo ='';
            $("input:checkbox[name='ActiveFind[channel][]']").each(function (){
                if($(this).is(':checked')){
                    checboxValueInfo = $(this).val();
                    var channelKey = 'channelUrl'+$(this).val();
                    if($("#"+channelKey).val() == ''){
                        alert('请填写'+$("#"+channelKey).attr('channelName'));
                        $("#"+channelKey).focus();
                        returnInfo = false;
                        return false;
                    }
                }
            });
            if(checboxValueInfo == ''){
                alert('平台为必选');
                return false;
            }
            if(returnInfo == false){
                return false;
            }
            $("[do=notnull]").each(function(){

                if($(this).val() == ''){
                    alert('请检查必填数据');
                    $(this).focus();
                    returnInfo = false;
                    return returnInfo;
                }
            });
            return returnInfo;
        })




        //相册删除按钮
        $("body").on("click",".delPic",function(){
            var htmlObj=$(this).parents(".form-group");
            htmlObj.next().remove();
            htmlObj.remove();
        });

        //上传按钮逻辑
        $("#file_upload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'uploadfileQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/cms/ajaxUpload',
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
                    picNum++;
                    var html='<div class="form-group">' +
                    '<label class="col-sm-3 control-label no-padding-right">图片</label>'+
                    '<div class="col-sm-9">'+
                    '<img src="'+path+'">'+
                    '<input type="hidden" name="ActiveCms[album_pic][]" value="'+path+'">'+
                    '<input type="button" class="delPic" value="删除">'+
                    '</div> </div>'+
                    '<div class="form-group">'+
                    '<label class="col-sm-3 control-label no-padding-right"></label>'+
                    '<div class="col-sm-9">'+
                    '<textarea style="width:350px; height:120px;" name="ActiveCms[album_content][]"></textarea>'+
                    '</div>'+
                    '</div>'
                    $("#album").prepend(html);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //封面图上传
        $("#coverUpload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            'multi':true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'coverUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/active/ajaxUpload',
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
                    $("#sCover").val(path);
                    $("#sCover_img").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //删除封面图
        $("#coverUploadClean").click(function(){
            $("#sCover").val();
            $("#sCover_img").attr('src','');
        })









    })
</script>
    <?php $this->endWidget(); ?>
