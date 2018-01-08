<?php
/* @var $this FilmFestivalController */
/* @var $data FilmFestival */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filmfest_name')); ?>:</b>
	<?php echo CHtml::encode($data->filmfest_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('url_param')); ?>:</b>
	<?php echo CHtml::encode($data->url_param); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user')); ?>:</b>
	<?php echo CHtml::encode($data->create_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_type')); ?>:</b>
	<?php echo CHtml::encode($data->status_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_top')); ?>:</b>
	<?php echo CHtml::encode($data->open_top); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('top_info')); ?>:</b>
	<?php echo CHtml::encode($data->top_info); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('open_recommend')); ?>:</b>
	<?php echo CHtml::encode($data->open_recommend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recommend_info')); ?>:</b>
	<?php echo CHtml::encode($data->recommend_info); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_recommend')); ?>:</b>
	<?php echo CHtml::encode($data->category_recommend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('top_bar_chart')); ?>:</b>
	<?php echo CHtml::encode($data->top_bar_chart); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_introduce')); ?>:</b>
	<?php echo CHtml::encode($data->open_introduce); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('introduce')); ?>:</b>
	<?php echo CHtml::encode($data->introduce); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_schedule')); ?>:</b>
	<?php echo CHtml::encode($data->open_schedule); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('schedule')); ?>:</b>
	<?php echo CHtml::encode($data->schedule); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_icon')); ?>:</b>
	<?php echo CHtml::encode($data->share_icon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_title')); ?>:</b>
	<?php echo CHtml::encode($data->share_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('share_describe')); ?>:</b>
	<?php echo CHtml::encode($data->share_describe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_time')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	*/ ?>

</div>