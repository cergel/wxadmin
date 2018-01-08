<?php
/* @var $this ActiveController */
/* @var $data Active */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->active_id), array('view', 'id'=>$data->active_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('summary')); ?>:</b>
	<?php echo CHtml::encode($data->summary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cover')); ?>:</b>
	<?php echo CHtml::encode($data->cover); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag')); ?>:</b>
	<?php echo CHtml::encode($data->tag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_name')); ?>:</b>
	<?php echo CHtml::encode($data->source_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_head')); ?>:</b>
	<?php echo CHtml::encode($data->source_head); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('source_summary')); ?>:</b>
	<?php echo CHtml::encode($data->source_summary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_link')); ?>:</b>
	<?php echo CHtml::encode($data->source_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_logo')); ?>:</b>
	<?php echo CHtml::encode($data->share_logo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_title')); ?>:</b>
	<?php echo CHtml::encode($data->share_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_summary')); ?>:</b>
	<?php echo CHtml::encode($data->share_summary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_link')); ?>:</b>
	<?php echo CHtml::encode($data->share_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_platform')); ?>:</b>
	<?php echo CHtml::encode($data->share_platform); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fill')); ?>:</b>
	<?php echo CHtml::encode($data->fill); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('online_time')); ?>:</b>
	<?php echo CHtml::encode($data->online_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('offline_time')); ?>:</b>
	<?php echo CHtml::encode($data->offline_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isonline')); ?>:</b>
	<?php echo CHtml::encode($data->isonline); ?>
	<br />

	*/ ?>

</div>