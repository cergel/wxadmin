<?php
/* @var $this ShowCommentReplyController */
/* @var $data ShowCommentReply */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('openId')); ?>:</b>
	<?php echo CHtml::encode($data->openId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('commentId')); ?>:</b>
	<?php echo CHtml::encode($data->commentId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channelId')); ?>:</b>
	<?php echo CHtml::encode($data->channelId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromId')); ?>:</b>
	<?php echo CHtml::encode($data->fromId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>