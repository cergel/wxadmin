<?php
/* @var $this CinemaHallFeatureController */
/* @var $model CinemaHallFeature */

$this->breadcrumbs=array(
	'Cinema Hall Features'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CinemaHallFeature', 'url'=>array('index')),
	array('label'=>'Create CinemaHallFeature', 'url'=>array('create')),
	array('label'=>'Update CinemaHallFeature', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CinemaHallFeature', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CinemaHallFeature', 'url'=>array('admin')),
);
?>

<h1>View CinemaHallFeature #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cinema_no',
		'cinema_name',
		'hall_no',
		'hall_name',
		'base_zan_num',
		'zan_num',
		'step_num',
		'created',
		'updated',
	),
)); ?>
