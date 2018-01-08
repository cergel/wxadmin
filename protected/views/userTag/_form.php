<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'ad-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!-- 标题 -->
        <?php if($model->isNewRecord){ ?>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'mobileNo', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'mobileNo',array('id'=>'mobileNo','size'=>60,'maxlength'=>30,'class' => 'col-xs-10','do'=>'notnull')); ?>
            </div>
        </div>
        <?php  } else{?>
            <?php echo $form->hiddenField($model,'mobileNo',array('id' => 'mobileNo'))?>
        <?php  } ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'openId', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'openId',array('id'=>'openId','size'=>12,'maxlength'=>12,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'nickname', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'nickname',array('id'=>'nickname','size'=>12,'maxlength'=>12,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'tag', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'tag',$model->getTagList('list') ,array('do'=>'tag','separator'=>'  '));?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'summary',array('size'=>12,'maxlength'=>12,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'openId',array('id' => 'openId_1'))?>
        <?php echo $form->hiddenField($model,'nickname',array('id' => 'nickname_1'))?>

        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" do="submit" id="submit">
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
    $(function () {

        //通过movieid获取影院名字
        $("#mobileNo").blur(function () {
            var mobileNo = $("#mobileNo").val();
            $.ajax({
                data: "mobileNo=" + mobileNo,
                url: "/userTag/getUserInfo?mobileNo="+mobileNo,
                type: "get",
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.error == undefined) {
                        $("#openId").val(data.openId);
                        $("#nickname").val(data.nickname);
                        $("#openId_1").val(data.openId);
                        $("#nickname_1").val(data.nickname);
                    } else {
                        $("#openId").val('');
                        $("#nickname").val('');
                        $("#openId_1").val('');
                        $("#nickname_1").val('');
                        alert(data.error);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });

        $("#submit").click(function(){
            var mobileNo = $("#mobileNo").val();
            var openId = $("#openId").val();
            var nickname = $("#nickname").val();
            var str="";
            $("input[do=tag]:checked").each(function(){
                str+=$(this).val();
            });

            if(mobileNo=='' || openId=='' || nickname=='' || str==''){
                alert('参数不完整');
                return false;
            }
        })
    });
</script>
    <?php $this->endWidget(); ?>
