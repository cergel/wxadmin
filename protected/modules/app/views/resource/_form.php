<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'resource-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sName',array('size'=>40,'maxlength'=>40,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'iChannelID', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model,'iChannelID',array($model::CHANNEL_ANDROID => 'Android', $model::CHANNEL_IOS => 'IOS' ), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('资源文件', 'Resource_sPath', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_resource/<?php echo $model->sPath;?>" height="200" />
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
        <div class="form-group" do="isAndroid">
            <?php echo $form->labelEx($model,'iIsLogo', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model,'iIsLogo',['1'=>'是','0'=>'否',], array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp; ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sNote', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sNote',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
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
