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
    'id'=>'fuli-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!-- 活动id-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_id', array('class'=>'col-sm-3 control-label no-padding-right ')); ?>
            <div class="col-sm-9">
                <?php if($model->isNewRecord){echo $form->textField($model,'a_id',array('id' => 'a_id','size'=>60,'maxlength'=>12,'class' => 'col-xs-10'));}
                else {echo $form->textField($model,'a_id',array('id' => 'a_id','size'=>60,'maxlength'=>12,'class' => 'col-xs-10','disabled'=>"disabled"));}  ?>
            </div>
        </div>
        <!-- 标题 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('dotype'=>'title','size'=>60,'maxlength'=>30,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <!-- 时间段 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'time_box', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'time_box',array('dotype'=>'time_box','size'=>60,'maxlength'=>60,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 平台  -->
        <div class="form-group">
            <?php echo CHtml::label('平台', '', array('class'=>'col-sm-3 control-label no-padding-right','required'=>true)); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'channel',Fuli::model()->getChannel('list'),array('separator'=>'  ','do'=>"checkbox"));?>
                </div>
            </div>
        </div>
        <!--  跳转链接地址  -->
        <?php foreach(Fuli::model()->getChannel('list') as $key=>$list){  ?>
            <div class="form-group">
                <?php echo CHtml::label($list.'链接', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php if(!empty($channelUrl[$key])){ ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value="<?php echo $channelUrl[$key];  ?>"  />
                    <?php }else{   ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value=""  />
                    <?php  } ?>
                </div>
            </div>

        <?php } ?>
        <!-- 状态  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right','required'=>true)); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!-- 封面  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'photo', array('class'=>'col-sm-3 control-label no-padding-right required','required'=>true)); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="photo" dotype="photo" src="<?php echo isset($model->photo)?$model->photo:'';?>" width="180" height="130" />
                </div>
                <div class="col-xs-10">
                    <div id="type_1" class="type_div" >
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于</div>
                    </div>
                    <div id="coverUpload">
                    </div>
                    <div id="coverUploadQueue" style="padding: 3px;">
                    </div>
                    <input  dotype="photo" id="valphoto" name="Fuli[photo]" type="hidden" value="<?php echo isset($model->photo)?$model->photo:'';?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('dotype'=>'start_time','class' => 'form-control date-timepicker')); ?>
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
                    <?php echo $form->textField($model,'end_time',array('dotype'=>'end_time','class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>


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
        <div class="form-group">
            <?php echo $form->labelEx($model, 'down_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'down_time',array('class' => 'form-control date-timepicker','do'=>"notnull")); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--投放城市-->
        <div class="form-group">
            <?php echo CHtml::label('定向城市', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CitySelectorWidget',array(
                        'name' => 'citys',
                        'selectedCities' => $selectedCitys
                    )); ?>
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

        //获取
        $("#a_id").blur(function () {
            var a_id = $("#a_id").val();
            if(a_id == '')return false;
            $.ajax({
                data: "aid=" + a_id,
                url: "/fuli/getApplyInfoAjax/"+a_id,
                type: "get",
                dataType: 'json',
                async: false,
                success: function (data) {
                    if(data){
                        $("[type=checkbox]").each(function (){
                            $(this).prop('checked',false);
                            var channelKey = 'channelUrl'+$(this).val();
                            $("#"+channelKey).val('');
                        });

                        for(var key in data){
                            $("[dotype="+key+"]").val(data[key]);
                        }
                        for(var channel in data['channel']){
                            $("#channelUrl"+channel).val(data['channel'][channel].url);
                            $("[do=checkbox]").each(function (){
                               // alert($(this).val());
                                if($(this).attr('value') == channel){
                                    $(this).prop('checked',true);
                                }
                            });
                            //$("input:checkbox[value='"+channel+"']").attr('checked','true');

                        }
                        $("#photo").attr('src',data['photo']);
                    }else{
                        alert('不能使用的报名活动内容');
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
            $("[do=checkbox]").each(function (){
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
            if(returnInfo == false){
                return false;
            }
            //对比时间
            var Fuli_up_time = $('#Fuli_up_time').val();
            var Fuli_down_time = $('#Fuli_down_time').val();
            if(datetime_to_unix(Fuli_up_time) > datetime_to_unix(Fuli_down_time)){
                alert('上线时间不得大于下线时间');
                $('#Fuli_up_time').focus();
                return false;
            }
            if($("#valphoto").val() == ''){
                alert('封面图为必填');
                return false;
            }

            return returnInfo;
        })

        function datetime_to_unix(datetime){
            var tmp_datetime = datetime.replace(/:/g,'-');
            tmp_datetime = tmp_datetime.replace(/ /g,'-');
            var arr = tmp_datetime.split("-");
            var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
            return parseInt(now.getTime()/1000);
        }

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
                    $("[dotype=photo]").val(path);
                    $("#photo").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });










    })
</script>
    <?php $this->endWidget(); ?>
