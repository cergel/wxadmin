<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'version-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
	
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'version', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'version',array('size'=>40,'maxlength'=>40,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'fromId', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'fromId',array('size'=>40,'maxlength'=>40,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'description', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('安装包', 'VersionFrom_path', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div>
                        <?php echo $model->path;?>
                    </div>
                    <!--
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_version/<?php echo $model->path;?>" height="200" />
                    </div>
                    -->
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'path',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">..。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '发布','0' => '下线'),array('separator'=>"   ")); ?>
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
