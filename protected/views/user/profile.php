<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
    '个人资料',
);
?>
<div class="page-header">
    <h1>个人资料</h1>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'profile-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal','autocomplete' => 'off')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sEmail', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sEmail',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sUserName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sUserName',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sNickName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <input type="text" style="display:none;" /><!-- 防止chrome自动填充 -->
                <?php echo $form->textField($model,'sNickName',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sPassword', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">

                <input type="text" style="display:none;" /><!-- 防止chrome自动填充 -->
                <?php echo $form->passwordField($model,'sPassword',array('value' => '', 'size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存' ; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div><!-- /.col -->
</div>
<?php $this->endWidget(); ?>