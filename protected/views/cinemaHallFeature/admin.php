<?php
/* @var $this CinemaHallFeatureController */
/* @var $model CinemaHallFeature */

$this->breadcrumbs=array(
	'Cinema Hall Features'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List CinemaHallFeature', 'url'=>array('index')),
	array('label'=>'Create CinemaHallFeature', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cinema-hall-feature-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Cinema Hall Features</h1>

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
	'id'=>'cinema-hall-feature-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'cinema_no',
		'cinema_name',
		'hall_no',
		'hall_name',
		'base_zan_num',
		/*
		'zan_num',
		'step_num',
		'created',
		'updated',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
