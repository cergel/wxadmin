<?php
/* @var $this VoiceCommentController */
/* @var $data VoiceComment */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('movie_id')); ?>:</b>
	<?php echo CHtml::encode($data->movie_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('movie_name')); ?>:</b>
	<?php echo CHtml::encode($data->movie_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_id')); ?>:</b>
	<?php echo CHtml::encode($data->open_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nick_name')); ?>:</b>
	<?php echo CHtml::encode($data->nick_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel_id')); ?>:</b>
	<?php echo CHtml::encode($data->channel_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('from')); ?>:</b>
	<?php echo CHtml::encode($data->from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('voice_url')); ?>:</b>
	<?php echo CHtml::encode($data->voice_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('times')); ?>:</b>
	<?php echo CHtml::encode($data->times); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order')); ?>:</b>
	<?php echo CHtml::encode($data->order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>