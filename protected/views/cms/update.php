<?php
$this->breadcrumbs=array(
	'内容'=>array('index'),
	'编辑',
);
?>

<h1>修改内容# <?php echo $model->iActive_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,)); ?>