<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'id', array('class'=>'col-sm-3 control-label no-padding-right',)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'id',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_id',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'ucid', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'ucid',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('内容', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
               <textarea style="width: 550px; height: 100px;"><?php echo $info; ?></textarea>
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
<?php $this->endWidget(); ?>
