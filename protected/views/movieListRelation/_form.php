<?php
/* @var $this MovieListRelationController */
/* @var $model MovieListRelation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'movie-list-relation-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'movie_list_id'); ?>
		<?php echo $form->textField($model,'movie_list_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'movie_list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'movie_id'); ?>
		<?php echo $form->textField($model,'movie_id'); ?>
		<?php echo $form->error($model,'movie_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'movie_desc'); ?>
		<?php echo $form->textField($model,'movie_desc',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'movie_desc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->textField($model,'sort'); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->