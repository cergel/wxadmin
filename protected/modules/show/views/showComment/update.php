<?php
/* @var $this ShowCommentController */
/* @var $model ShowComment */

$this->breadcrumbs=array(
	'评论'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
	<h1>
		编辑评论
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			#<?php echo $model->id; ?>
		</small>
	</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
