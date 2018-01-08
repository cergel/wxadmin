<?php
    Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cinema-group-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sName',array('size'=>60,'maxlength'=>255,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('定向影城', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CinemaSelectorWidget',array(
                        'name' => 'cinemas',
                        'selectedCinemas' => $selectedCinemas
                    )); ?>
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
