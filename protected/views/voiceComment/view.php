<?php
/* @var $this VoiceCommentController */
/* @var $model VoiceComment */

$this->breadcrumbs=array(
	'Voice Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List VoiceComment', 'url'=>array('index')),
	array('label'=>'Create VoiceComment', 'url'=>array('create')),
	array('label'=>'Update VoiceComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete VoiceComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage VoiceComment', 'url'=>array('admin')),
);
?>

<h1>View VoiceComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'movie_id',
		'movie_name',
		'open_id',
		'nick_name',
		'channel_id',
		'content',
		'from',
		'voice_url',
		'times',
		'order',
		'created',
	),
)); ?>
