<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
));
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->clientScript->registerScript('form', "

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
");
?>

<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('id' => 'title', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'startTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'startTime', array('id' => 'startTime', 'size' => 60, 'maxlength' => 20, 'class' => 'form-control  date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'endTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'endTime', array('id' => 'endTime', 'size' => 60, 'maxlength' => 20, 'class' => 'form-control  date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('图片', 'picture', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/weixin_find/<?php echo $model->id . '/' . $model->picture;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-9">
                    <?php echo $form->fileField($model,'picture',array('class' => 'col-xs-4'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 问@吕超。</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('id' => 'status','separator' => ' ')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'content', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'content', array('id' => 'content', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
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
        <input id="modelId"  type="hidden" value="<?php echo isset($model->id)?$model->id:0;?>">
    </div>
</div>

<script>
    $(function () {

        $("#submit").click(function(){
            var startTime = $("#startTime").val();
            var endTime = $("#endTime").val();

            if(startTime!='' && endTime!=''){
                var flag=false;
                $.ajax({
                    data: "startTime="+startTime+"&endTime="+endTime,
                    url: "/weixin/find/checkTime",
                    type: "POST",
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if(data.succ==1){
                            flag = true;
                        }else{
                            alert(data.msg);
                            return false;
                        }
                    },
                    error: function ($data) {
                        alert("网络错误请重试")
                    }
                });
                return flag;
            }


        })
    });
</script>

<?php
$this->endWidget();
?>


