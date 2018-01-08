
<?php
Yii::app()->clientScript->registerScript('form', "
    function update_form() {
        if ($('#Notice_issend :radio:checked').val() == '1') {
            $('.isshow').show();
        } else if ($('#Notice_issend :radio:checked').val() == '2') {
            $('.isshow').hide();
        } else {
            $('.isshow').hide();
        }
    }
	update_form();
    $('#Notice_issend :radio').click(function () {
        update_form();
    });");

 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notice-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>255,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sContext', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sContext',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iPushid', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iPushid',array('size'=>50,'maxlength'=>50,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sUrl', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sUrl',array('size'=>60,'maxlength'=>255,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            	<?php echo $form->dropDownList($model,'iType',Notice::getCategoryList(), array('separator' => ' ', 'empty' => '请选择分类')); ?>
            </div>
        </div>
        <!-- 
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iIsdel', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iIsdel',array('size'=>1,'maxlength'=>1,'class' => 'col-xs-10')); ?>
            </div>
        </div>
         -->
        <div class="form-group " id="Notice_issend">
            <?php echo $form->labelEx($model, 'iIssend', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            	<?php echo $form->radioButtonList($model,'iIssend',Notice::getIssend(), array('separator' => ' ')); ?>
            </div>
        </div>
        
         <div class="form-group isshow">
            <?php echo $form->labelEx($model, 'andriodTest', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            	 <?php echo $form->textArea($model,'andriodTest',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        <div class="form-group isshow">
            <?php echo $form->labelEx($model, 'iosTest', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            	 <?php echo $form->textArea($model,'iosTest',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        <div class="form-group isshow">
            <?php echo $form->labelEx($model, 'iSendOn', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            	<?php echo $form->radioButtonList($model,'iSendOn',Notice::getSendOn(), array('separator' => ' ')); ?>
            </div>
        </div>
        
        <!-- 
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iSendtime', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iSendtime',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
         -->
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
