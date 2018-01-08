<?php
/* @var $this MovieListController */
/* @var $model MovieList */

$this->breadcrumbs=array(
	'Movie Lists'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List MovieList', 'url'=>array('index')),
	array('label'=>'Create MovieList', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#movie-list-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Movie Lists</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'movie-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'desc',
		'author',
		'author_image',
		'author_desc',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
