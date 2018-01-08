<?php
/* @var $this MovieListController */
/* @var $data MovieList */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('desc')); ?>:</b>
	<?php echo CHtml::encode($data->desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_image')); ?>:</b>
	<?php echo CHtml::encode($data->author_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_desc')); ?>:</b>
	<?php echo CHtml::encode($data->author_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('movie_num')); ?>:</b>
	<?php echo CHtml::encode($data->movie_num); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('collect_num')); ?>:</b>
	<?php echo CHtml::encode($data->collect_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('collect_num_really')); ?>:</b>
	<?php echo CHtml::encode($data->collect_num_really); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('read_num')); ?>:</b>
	<?php echo CHtml::encode($data->read_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('read_num_really')); ?>:</b>
	<?php echo CHtml::encode($data->read_num_really); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_image')); ?>:</b>
	<?php echo CHtml::encode($data->share_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_desc')); ?>:</b>
	<?php echo CHtml::encode($data->share_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_platform')); ?>:</b>
	<?php echo CHtml::encode($data->share_platform); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('online_time')); ?>:</b>
	<?php echo CHtml::encode($data->online_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	*/ ?>

</div>