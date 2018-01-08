<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 11:28
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('form', "
   $('.date-timepicker').datetimepicker({
            format:\"YYYY-MM-DD HH:mm:ss\"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
   $('#RedActive_local :checkbox').click(function () {
		if($(this).val() == '0'){
			if($(this).is(':checked')){
				checkedLocal(1)
			}else{
				checkedLocal(2)
			}
		}else{
			if(! $(this).is(':checked')){
				$('#RedActive_local_0').prop('checked',false);
			}
		}
    });
    $('#RedActive_channel :checkbox').click(function () {
		if($(this).val() == '0'){
			if($(this).is(':checked')){
				checkedChannel(1)
			}else{
				checkedChannel(2)
			}
		}else{
			if(! $(this).is(':checked')){
				$('#RedActive_channel_0').prop('checked',false);
			}
		}
    });
    function checkedLocal (t)
		{
			$('#RedActive_local :checkbox').each(function(){
				if(t == 1)
					this.checked = true;
				else
					this.checked = false;
			  });
		}
	function checkedChannel (t)
		{
			$('#RedActive_channel :checkbox').each(function(){
				if(t == 1)
					this.checked = true;
				else
					this.checked = false;
			  });
		}

");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'red-active-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <!-- 红点活动名称  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_name', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--红点配置位置-->
        <div class="form-group">
            <?php echo CHtml::label('红点配置位置', '', array('required' => true,'class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'local',$model->getLocalkey(),array('separator'=>'  '));?>
                </div>
            </div>
        </div>
        <!-- 定向平台 -->
        <div class="form-group">
            <?php echo CHtml::label('定向平台', '', array('required' => true,'class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'channel',$model->getChannelkey(),array('separator'=>'  '));?>
                </div>
            </div>
        </div>
        <!--开始定向版本-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_start_release', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_start_release', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--结束定向版本-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_end_release', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_end_release', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--开始投放时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_start_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'a_start_time', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--结束投放时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_end_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'a_end_time', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--定义提交按钮-->
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '提交' : '保存'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    取消
                </button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
