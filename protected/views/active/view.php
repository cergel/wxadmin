<?php
/* @var $this ActiveController */
/* @var $model Active */

$this->breadcrumbs=array(
	'Actives'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Active', 'url'=>array('index')),
	array('label'=>'Create Active', 'url'=>array('create')),
	array('label'=>'Update Active', 'url'=>array('update', 'id'=>$model->active_id)),
	array('label'=>'Delete Active', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->active_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Active', 'url'=>array('admin')),
);
?>

<h1>View Active #<?php echo $model->active_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'active_id',
		'title',
		'summary',
		'cover',
		'tag',
		'source_name',
		'source_head',
		'source_summary',
		'source_link',
		'share_logo',
		'share_title',
		'share_summary',
		'share_link',
		'share_platform',
		'fill',
		'online_time',
		'offline_time',
		'isonline',
	),
)); ?>
