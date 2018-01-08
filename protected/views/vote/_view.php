<?php
/* @var $this VoteController */
/* @var $data Vote */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_photo')); ?>:</b>
	<?php echo CHtml::encode($data->share_photo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_title')); ?>:</b>
	<?php echo CHtml::encode($data->share_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_content')); ?>:</b>
	<?php echo CHtml::encode($data->share_content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_channel')); ?>:</b>
	<?php echo CHtml::encode($data->share_channel); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_flag')); ?>:</b>
	<?php echo CHtml::encode($data->end_flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	*/ ?>

</div>