<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 15:54
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");

Yii::app()->clientScript->registerScript('form', "
    function update_form() {
 		var thisid = $('#IconConfig_type :radio:checked').val();
		if(thisid){
			$('.form-group').hide();
			$('[isall=1]').show();
			$('[type'+thisid+'='+thisid+']').show();
		}else{
			$('#IconConfig_type_0').attr('checked','checked');
			$('#IconConfig_type_0').click();
		}
    }

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('#IconConfig_type :radio').click(function () {
        update_form();
    });

    update_form();
	var isnew = '". $model->isNewRecord."';
	if(!isnew){
		$('#IconConfig_type :radio').attr('disabled','disabled');
	}
");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'icon-config-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!-- 活动主题 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 资源分类  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'type', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'type',array('1'=>'ICON组合'), array('separator' => ' ')); ?>
            </div>
        </div>
        <?php if (!empty($model->id)){?>
            <input type="hidden" id="IconConfig[type]" name="IconConfig[type]" value=<?php echo $model->type;?>>
        <?php }?>

        <!-- 封面  -->
        <div class="form-group" type1="1" align="center">
            <div class="col-sm-14" >
                <table width="80%" >
                    <tr>
                        <td>
                            <?php echo Chtml::label('图标1', 'icon1', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标1_按下', 'icon1_on', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标2', 'icon2', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标2_按下', 'icon2_on', array('required' => true));?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg1" src="<?php echo isset($model->icon1)?$model->icon1:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean1" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload1">
                                    </div>
                                    <div id="iconUploadQueue1" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput1" name="IconConfig[icon1]" type="hidden" value="<?php echo isset($model->icon1)?$model->icon1:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg2" src="<?php echo isset($model->icon1_on)?$model->icon1_on:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean2" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload2">
                                    </div>
                                    <div id="iconUploadQueue2" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput2" name="IconConfig[icon1_on]" type="hidden" value="<?php echo isset($model->icon1_on)?$model->icon1_on:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg3" src="<?php echo isset($model->icon2)?$model->icon2:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean3" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload3">
                                    </div>
                                    <div id="iconUploadQueue3" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput3" name="IconConfig[icon2]" type="hidden" value="<?php echo isset($model->icon2)?$model->icon2:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg4" src="<?php echo isset($model->icon2_on)?$model->icon2_on:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean4" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload4">
                                    </div>
                                    <div id="iconUploadQueue4" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput4" name="IconConfig[icon2_on]" type="hidden" value="<?php echo isset($model->icon2_on)?$model->icon2_on:'';?>">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo Chtml::label('图标3', 'icon3', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标3_按下', 'icon3_on', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标4', 'icon4', array('required' => true));?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标4_按下', 'icon4_on', array('required' => true));?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg5" src="<?php echo isset($model->icon3)?$model->icon3:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean5" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload5">
                                    </div>
                                    <div id="iconUploadQueue5" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput5" name="IconConfig[icon3]" type="hidden" value="<?php echo isset($model->icon3)?$model->icon3:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg6" src="<?php echo isset($model->icon3_on)?$model->icon3_on:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean6" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload6">
                                    </div>
                                    <div id="iconUploadQueue6" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput6" name="IconConfig[icon3_on]" type="hidden" value="<?php echo isset($model->icon3_on)?$model->icon3_on:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg7" src="<?php echo isset($model->icon4)?$model->icon4:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean7" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload7">
                                    </div>
                                    <div id="iconUploadQueue7" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput7" name="IconConfig[icon4]" type="hidden" value="<?php echo isset($model->icon4)?$model->icon4:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg8" src="<?php echo isset($model->icon4_on)?$model->icon4_on:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean8" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload8">
                                    </div>
                                    <div id="iconUploadQueue8" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput8" name="IconConfig[icon4_on]" type="hidden" value="<?php echo isset($model->icon4_on)?$model->icon4_on:'';?>">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo Chtml::label('图标5', 'icon1');?>
                        </td>
                        <td>
                            <?php echo Chtml::label('图标5_按下', 'icon1_on');?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg9" src="<?php echo isset($model->icon5)?$model->icon5:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean9" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload9">
                                    </div>
                                    <div id="iconUploadQueue9" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput9" name="IconConfig[icon5]" type="hidden" value="<?php echo isset($model->icon5)?$model->icon5:'';?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <div style="height:150px;width:250px;">
                                    <img id="iconImg10" src="<?php echo isset($model->icon5_on)?$model->icon5_on:'';?>" width="180" height="130" />
                                    <input id="iconUploadClean10" type="button" value="删除">
                                </div>
                                <div class="col-xs-10">
                                    <div id="iconUpload10">
                                    </div>
                                    <div id="iconUploadQueue10" style="padding: 3px;">
                                    </div>
                                    <input id="iconInput10" name="IconConfig[icon5_on]" type="hidden" value="<?php echo isset($model->icon5_on)?$model->icon5_on:'';?>">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- loading图 -->
        <div class="form-group" type2="2">
            <?php echo $form->labelEx($model, 'loading', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:150px;width:250px;">
                    <img id="loading_img" src="<?php echo isset($model->loading)?$model->loading:'';?>" width="180" height="130" />
                    <input id="loadingUploadClean" type="button" value="删除">
                </div>
                <div class="col-xs-10">
                    <div id="loadingUpload">
                    </div>
                    <div id="loadingUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="loadingInput" name="IconConfig[loading]" type="hidden" value="<?php echo isset($model->loading)?$model->loading:'';?>">
                </div>
            </div>
        </div>

        <!-- icon名称选中颜色 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'icon_color', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div class="input-group col-xs-3">
                         <?php echo $form->textField($model,'icon_color',array('size'=>60,'maxlength'=>60,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
        </div>
        <!--  活动开始时间 -->
        <div class="form-group" isall="1">
            <?php echo Chtml::label('开始时间', 'start_time', array('required' => true, 'class'=>'col-sm-2 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!-- 活动结束时间 -->
        <div class="form-group" isall="1">
            <?php echo Chtml::label('活动结束时间', 'end_time', array('required' => true, 'class'=>'col-sm-2 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!-- 投放平台  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'platform', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'platform',array('8' => 'IOS', '9' => 'Android', '3' => '微信', '28' => '手Q'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!-- 状态  ： 发布、未发布 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-2 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '发布', '0' => '未发布'), array('separator' => ' ')); ?>
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
    $(function() {
        for(var i = 1 ; i < 11 ; i++) {
            //封面图上传
            $("#iconUpload"+i).uploadify({
                //开启调试
                'debug': false,
                //是否自动上传
                'auto': true,
                'buttonText': '上传',
                'multi': true,//允许批量上传
                //flash
                'swf': "/assets/js/uploadify/uploadify.swf",
                //文件选择后的容器ID
                'queueID': 'iconUploadQueue'+i,
                'fileObjName': 'UpLoadFile',
                'uploader': '/app/iconConfig/ajaxUpload',
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
                    var id = $(this).attr('original').attr('id');
                    var index = id.substring(10),
                        jsonObj = jQuery.parseJSON(data),
                        path = jsonObj.path,
                        succ = jsonObj.success;
                    if (succ == 1) {
                        $("#iconInput"+index).val(path);
                        $("#iconImg"+index).attr('src', path);
                    } else {
                        alert('图片上传失败')
                    }
                }
            });
            //分享图片 删除
            $("#iconUploadClean"+i).click(function () {
                var id = $(this).attr('id');
                var index = id.substring(15);
                $('#iconInput'+index).val('');
                $("#iconImg"+index).attr('src', '');
            })
        }
        //分享-图片上传
        $("#loadingUpload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            'multi':true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'loadingUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/app/iconConfig/ajaxUpload',
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
                    $('#loadingInput').val(path);
                    $("#loading_img").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //分享图片 删除
        $("#loadingUploadClean").click(function(){
            $('#loadingInput').val('');
            $("#loading_img").attr('src','');
        })

    })

</script>
<?php $this->endWidget(); ?>
