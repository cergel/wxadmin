<?php
/* @var $this ActiveController */
/* @var $model Active */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'active_id'); ?>
		<?php echo $form->textField($model,'active_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'summary'); ?>
		<?php echo $form->textArea($model,'summary',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cover'); ?>
		<?php echo $form->textField($model,'cover',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tag'); ?>
		<?php echo $form->textArea($model,'tag',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_name'); ?>
		<?php echo $form->textField($model,'source_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_head'); ?>
		<?php echo $form->textField($model,'source_head',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_summary'); ?>
		<?php echo $form->textArea($model,'source_summary',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_link'); ?>
		<?php echo $form->textField($model,'source_link',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_logo'); ?>
		<?php echo $form->textField($model,'share_logo',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_title'); ?>
		<?php echo $form->textField($model,'share_title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_summary'); ?>
		<?php echo $form->textField($model,'share_summary',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_link'); ?>
		<?php echo $form->textField($model,'share_link',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'share_platform'); ?>
		<?php echo $form->textField($model,'share_platform',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fill'); ?>
		<?php echo $form->textField($model,'fill'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'online_time'); ?>
		<?php echo $form->textField($model,'online_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'offline_time'); ?>
		<?php echo $form->textField($model,'offline_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isonline'); ?>
		<?php echo $form->textField($model,'isonline'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->