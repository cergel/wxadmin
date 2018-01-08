<?php
/* @var $this MovieListController */
/* @var $model MovieList */

$this->breadcrumbs=array(
	'Movie Lists'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List MovieList', 'url'=>array('index')),
	array('label'=>'Create MovieList', 'url'=>array('create')),
	array('label'=>'Update MovieList', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MovieList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MovieList', 'url'=>array('admin')),
);
?>

<h1>View MovieList #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'desc',
		'author',
		'author_image',
		'author_desc',
		'movie_num',
		'collect_num',
		'collect_num_really',
		'read_num',
		'read_num_really',
		'share_image',
		'share_desc',
		'share_platform',
		'status',
		'online_time',
		'create_time',
		'update_time',
	),
)); ?>
