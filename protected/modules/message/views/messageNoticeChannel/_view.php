<?php
/* @var $this MessageNoticeChannelController */
/* @var $data MessageNoticeChannel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message_notice_id')); ?>:</b>
	<?php echo CHtml::encode($data->message_notice_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('push_id')); ?>:</b>
	<?php echo CHtml::encode($data->push_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel')); ?>:</b>
	<?php echo CHtml::encode($data->channel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('push_url')); ?>:</b>
	<?php echo CHtml::encode($data->push_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	*/ ?>

</div>