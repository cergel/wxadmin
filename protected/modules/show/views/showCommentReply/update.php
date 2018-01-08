<?php
/* @var $this ShowCommentReplyController */
/* @var $model ShowCommentReply */

$this->breadcrumbs=array(
	'Show Comment Replies'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ShowCommentReply', 'url'=>array('index')),
	array('label'=>'Create ShowCommentReply', 'url'=>array('create')),
	array('label'=>'View ShowCommentReply', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShowCommentReply', 'url'=>array('admin')),
);
?>

<h1>Update ShowCommentReply <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>