<?php
/* @var $this MovieOrderController */
/* @var $model MovieOrder */

$this->breadcrumbs=array(
	'Movie Orders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MovieOrder', 'url'=>array('index')),
	array('label'=>'Create MovieOrder', 'url'=>array('create')),
	array('label'=>'Update MovieOrder', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MovieOrder', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MovieOrder', 'url'=>array('admin')),
);
?>

<h1>View MovieOrder #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'movie_id',
		'movie_name',
		'start_time',
		'end_time',
		'pos',
		'status',
		'created',
		'updated',
		'content',
	),
)); ?>
