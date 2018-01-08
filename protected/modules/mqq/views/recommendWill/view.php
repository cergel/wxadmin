<?php
/* @var $this RecommendWillController */
/* @var $model RecommendWill */

$this->breadcrumbs=array(
	'Recommend Wills'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List RecommendWill', 'url'=>array('index')),
	array('label'=>'Create RecommendWill', 'url'=>array('create')),
	array('label'=>'Update RecommendWill', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RecommendWill', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RecommendWill', 'url'=>array('admin')),
);
?>

<h1>View RecommendWill #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'movie_id',
		'movie_name',
		'remark',
		'start_time',
		'end_time',
		'link',
		'order',
	),
)); ?>
