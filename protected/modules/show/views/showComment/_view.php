<?php
/* @var $this ShowCommentController */
/* @var $data ShowComment */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('openId')); ?>:</b>
	<?php echo CHtml::encode($data->openId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score')); ?>:</b>
	<?php echo CHtml::encode($data->score); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channelId')); ?>:</b>
	<?php echo CHtml::encode($data->channelId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_count')); ?>:</b>
	<?php echo CHtml::encode($data->reply_count); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('favor_count')); ?>:</b>
	<?php echo CHtml::encode($data->favor_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('base_favor_count')); ?>:</b>
	<?php echo CHtml::encode($data->base_favor_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_name')); ?>:</b>
	<?php echo CHtml::encode($data->project_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_name')); ?>:</b>
	<?php echo CHtml::encode($data->type_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromId')); ?>:</b>
	<?php echo CHtml::encode($data->fromId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel_type')); ?>:</b>
	<?php echo CHtml::encode($data->channel_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
	<?php echo CHtml::encode($data->ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('checkstatus')); ?>:</b>
	<?php echo CHtml::encode($data->checkstatus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	*/ ?>

</div>