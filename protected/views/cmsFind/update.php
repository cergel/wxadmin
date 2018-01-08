<?php
$this->breadcrumbs=array(
	'发现'=>array('index?type='.(!empty($_GET['type'])?$_GET['type']:'')),
	'编辑',
);
?>

<h1>修改发现# <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'channelUrl'=>$channelUrl)); ?>