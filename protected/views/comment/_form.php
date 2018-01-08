<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'content', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'baseFavorCount', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'baseFavorCount',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('标签', '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model, 'tag', $tag, array('separator' => '  ')); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'created', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-3">
                <?php echo $form->textField($model,'created',array('size'=>20,'maxlength'=>20,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
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
