<?php
/* @var $this ActiveController */
/* @var $model Active */

$this->breadcrumbs=array(
	'Actives'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Active', 'url'=>array('index')),
	array('label'=>'Create Active', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#active-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Actives</h1>

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
	'id'=>'active-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'active_id',
		'title',
		'summary',
		'cover',
		'tag',
		'source_name',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
