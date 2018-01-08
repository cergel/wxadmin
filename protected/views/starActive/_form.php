<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('form', "
   $('.date-timepicker').datetimepicker({
            format:\"YYYY-MM-DD\"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });

");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'star-active-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <!-- 活动名称  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'a_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_name', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--活动日期-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'a_date', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'a_date', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!-- 活动标签 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'a_tag', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_tag', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--活动标题-->
        <div class="form-group" itype2="2">
            <?php echo $form->labelEx($model, 'a_title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'a_title', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--活动详情-->
        <div class="form-group" itype2="2">
            <?php echo $form->labelEx($model, 'a_detail', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textArea($model, 'a_detail', array('size' => 60, 'rows' => 5, 'class' => 'col-xs-10')); ?>
                （100字以内）
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
