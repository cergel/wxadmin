<?php
/* @var $this CinemaNotificationController */
/* @var $model CinemaNotification */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'iNotificationID'); ?>
		<?php echo $form->textField($model,'iNotificationID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sName'); ?>
		<?php echo $form->textField($model,'sName',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sContent'); ?>
		<?php echo $form->textArea($model,'sContent',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iShow'); ?>
		<?php echo $form->textField($model,'iShow'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iStartAt'); ?>
		<?php echo $form->textField($model,'iStartAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iEndAt'); ?>
		<?php echo $form->textField($model,'iEndAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iStatus'); ?>
		<?php echo $form->textField($model,'iStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iCreated'); ?>
		<?php echo $form->textField($model,'iCreated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iUpdated'); ?>
		<?php echo $form->textField($model,'iUpdated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>