<?php
/* @var $this VoteController */
/* @var $model Vote */

$this->breadcrumbs=array(
	'Votes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Vote', 'url'=>array('index')),
	array('label'=>'Create Vote', 'url'=>array('create')),
	array('label'=>'Update Vote', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Vote', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vote', 'url'=>array('admin')),
);
?>

<h1>View Vote #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'share_photo',
		'share_title',
		'share_content',
		'share_channel',
		'end_time',
		'end_flag',
		'created',
		'updated',
	),
)); ?>
