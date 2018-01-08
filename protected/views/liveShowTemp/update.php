<?php
$this->breadcrumbs=array(
	'直播预热'=>array('index'),
	'编辑',
);
?>

<h1>修改内容# <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,)); ?>