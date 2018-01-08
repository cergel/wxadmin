<?php
/* @var $this ShowCommentController */
/* @var $model ShowComment */

$this->breadcrumbs=array(
	'Show Comments'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ShowComment', 'url'=>array('index')),
	array('label'=>'Create ShowComment', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#show-comment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Show Comments</h1>

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
	'id'=>'show-comment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'openId',
		'project_id',
		'content',
		'score',
		'channelId',
		/*
		'reply_count',
		'favor_count',
		'base_favor_count',
		'project_name',
		'type_name',
		'fromId',
		'channel_type',
		'ip',
		'checkstatus',
		'status',
		'created',
		'updated',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
