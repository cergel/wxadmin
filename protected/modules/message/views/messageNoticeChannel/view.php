<?php
/* @var $this MessageNoticeChannelController */
/* @var $model MessageNoticeChannel */

$this->breadcrumbs=array(
	'Message Notice Channels'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MessageNoticeChannel', 'url'=>array('index')),
	array('label'=>'Create MessageNoticeChannel', 'url'=>array('create')),
	array('label'=>'Update MessageNoticeChannel', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MessageNoticeChannel', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MessageNoticeChannel', 'url'=>array('admin')),
);
?>

<h1>View MessageNoticeChannel #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'message_notice_id',
		'push_id',
		'channel',
		'push_url',
		'status',
		'create_time',
		'update_time',
	),
)); ?>
