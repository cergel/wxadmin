<?php
/* @var $this MessageNoticeController */
/* @var $model MessageNotice */

$this->breadcrumbs=array(
	'Message Notices'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List MessageNotice', 'url'=>array('index')),
	array('label'=>'Create MessageNotice', 'url'=>array('create')),
	array('label'=>'Update MessageNotice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MessageNotice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MessageNotice', 'url'=>array('admin')),
);
?>

<h1>View MessageNotice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'msg_type',
		'title',
		'content',
		'msg_pic',
		'task_id',
		'push_msg',
		'status',
		'push_date',
		'create_time',
		'update_time',
	),
)); ?>
