<?php
/* @var $this MessageNoticeChannelController */
/* @var $model MessageNoticeChannel */

$this->breadcrumbs=array(
	'Message Notice Channels'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MessageNoticeChannel', 'url'=>array('index')),
	array('label'=>'Create MessageNoticeChannel', 'url'=>array('create')),
	array('label'=>'View MessageNoticeChannel', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MessageNoticeChannel', 'url'=>array('admin')),
);
?>

<h1>Update MessageNoticeChannel <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>