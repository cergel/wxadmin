<?php
/* @var $this MovieListController */
/* @var $model MovieList */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'desc'); ?>
		<?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'author'); ?>
		<?php echo $form->textField($model,'author',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'author_image'); ?>
		<?php echo $form->textField($model,'author_image',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'author_desc'); ?>
		<?php echo $form->textField($model,'author_desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'movie_num'); ?>
		<?php echo $form->textField($model,'movie_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'collect_num'); ?>
		<?php echo $form->textField($model,'collect_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'collect_num_really'); ?>
		<?php echo $form->textField($model,'collect_num_really'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'read_num'); ?>
		<?php echo $form->textField($model,'read_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'read_num_really'); ?>
		<?php echo $form->textField($model,'read_num_really'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_image'); ?>
		<?php echo $form->textField($model,'share_image',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_desc'); ?>
		<?php echo $form->textField($model,'share_desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_platform'); ?>
		<?php echo $form->textField($model,'share_platform',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'online_time'); ?>
		<?php echo $form->textField($model,'online_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->