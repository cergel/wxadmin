<?php
/* @var $this CinemaHallFeatureController */
/* @var $model CinemaHallFeature */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cinema_no'); ?>
		<?php echo $form->textField($model,'cinema_no',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cinema_name'); ?>
		<?php echo $form->textField($model,'cinema_name',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hall_no'); ?>
		<?php echo $form->textField($model,'hall_no',array('size'=>35,'maxlength'=>35)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hall_name'); ?>
		<?php echo $form->textField($model,'hall_name',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'base_zan_num'); ?>
		<?php echo $form->textField($model,'base_zan_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'zan_num'); ?>
		<?php echo $form->textField($model,'zan_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'step_num'); ?>
		<?php echo $form->textField($model,'step_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->