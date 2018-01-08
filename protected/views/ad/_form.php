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
	'id'=>'ad-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('图片', 'sPath', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/ad/<?php echo date('Y-m-d', $model->iCreated) . '/' . $model->sPath;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'sPath',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">小于512Kb。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sLink', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sLink',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iType',Ad::getTypeList(), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iShowAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iShowAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iHideAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iHideAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
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
        <div class="form-group">
            <?php echo CHtml::label('定向影片', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.MovieSelectorWidget',array(
                        'name' => 'movies',
                        'selectedMovies' => $selectedMovies
                    )); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iStatus', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iStatus',array('1' => '上线', '0' => '预上线'), array('separator' => ' ')); ?>
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
