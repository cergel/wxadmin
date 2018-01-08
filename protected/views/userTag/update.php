<?php
$this->breadcrumbs=array(
	'标签'=>array('index'),
	'编辑',
);
?>

<h1>修改用户标签# <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,)); ?>