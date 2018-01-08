<?php
/* @var $this MovieListRelationController */
/* @var $model MovieListRelation */

$this->breadcrumbs=array(
	'Movie List Relations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MovieListRelation', 'url'=>array('index')),
	array('label'=>'Create MovieListRelation', 'url'=>array('create')),
	array('label'=>'Update MovieListRelation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MovieListRelation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MovieListRelation', 'url'=>array('admin')),
);
?>

<h1>View MovieListRelation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'movie_list_id',
		'movie_id',
		'movie_desc',
		'sort',
	),
)); ?>
