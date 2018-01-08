<?php
/* @var $this ShowCommentReplyController */
/* @var $model ShowCommentReply */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'show-comment-reply-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'openId'); ?>
		<?php echo $form->textField($model,'openId',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'openId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'commentId'); ?>
		<?php echo $form->textField($model,'commentId'); ?>
		<?php echo $form->error($model,'commentId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'channelId'); ?>
		<?php echo $form->textField($model,'channelId'); ?>
		<?php echo $form->error($model,'channelId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fromId'); ?>
		<?php echo $form->textField($model,'fromId',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'fromId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->