<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<?php if ($model->isNewRecord){?>
	        <div class="form-group">
	            <?php echo $form->labelEx($model, 'name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
	            <div class="col-sm-9">
	                <?php echo $form->textArea($model,'name',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
	            </div>
	        </div> 
	        <div class="form-group">
	         <?php echo Chtml::label(' ', 'name_test', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
	            <div class="col-sm-9">
	               	多个敏感词同时添加请以顿号隔开(、),重复或者已经存在的敏感词则不会被保存。
	            </div>
	        </div> 
        <?php }else{?>
	        <div class="form-group">
	            <?php echo $form->labelEx($model,'name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
	            <div class="col-sm-9">
	                <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
	            </div>
	        </div>
        <?php }?>
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
