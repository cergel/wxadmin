<?php
/* @var $this ShowCommentController */
/* @var $model ShowComment */

$this->breadcrumbs=array(
	'Show Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ShowComment', 'url'=>array('index')),
	array('label'=>'Create ShowComment', 'url'=>array('create')),
	array('label'=>'Update ShowComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShowComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShowComment', 'url'=>array('admin')),
);
?>

<h1>View ShowComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'openId',
		'project_id',
		'content',
		'score',
		'channelId',
		'reply_count',
		'favor_count',
		'base_favor_count',
		'project_name',
		'type_name',
		'fromId',
		'channel_type',
		'ip',
		'checkstatus',
		'status',
		'created',
		'updated',
	),
)); ?>
