<?php
/* @var $this LogController */
/* @var $model Log */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'iLogID'); ?>
		<?php echo $form->textField($model,'iLogID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iUserID'); ?>
		<?php echo $form->textField($model,'iUserID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sUri'); ?>
		<?php echo $form->textField($model,'sUri',array('size'=>60,'maxlength'=>1000)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sData'); ?>
		<?php echo $form->textArea($model,'sData',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sIp'); ?>
		<?php echo $form->textField($model,'sIp',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iCreated'); ?>
		<?php echo $form->textField($model,'iCreated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->