<?php
/* @var $this MessageNoticeChannelController */
/* @var $model MessageNoticeChannel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-notice-channel-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message_notice_id'); ?>
		<?php echo $form->textField($model,'message_notice_id'); ?>
		<?php echo $form->error($model,'message_notice_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_id'); ?>
		<?php echo $form->textField($model,'push_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'push_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'channel'); ?>
		<?php echo $form->textField($model,'channel'); ?>
		<?php echo $form->error($model,'channel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'push_url'); ?>
		<?php echo $form->textField($model,'push_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'push_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
		<?php echo $form->error($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->