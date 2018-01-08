<?php
/* @var $this CinemaHallFeatureController */
/* @var $data CinemaHallFeature */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cinema_no')); ?>:</b>
	<?php echo CHtml::encode($data->cinema_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cinema_name')); ?>:</b>
	<?php echo CHtml::encode($data->cinema_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hall_no')); ?>:</b>
	<?php echo CHtml::encode($data->hall_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hall_name')); ?>:</b>
	<?php echo CHtml::encode($data->hall_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('base_zan_num')); ?>:</b>
	<?php echo CHtml::encode($data->base_zan_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zan_num')); ?>:</b>
	<?php echo CHtml::encode($data->zan_num); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('step_num')); ?>:</b>
	<?php echo CHtml::encode($data->step_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	*/ ?>

</div>