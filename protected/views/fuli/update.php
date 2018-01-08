<?php
$this->breadcrumbs=array(
	'福利频道'=>array('index'),
	'编辑',
);
?>

<h1>修改发现# <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'selectedCitys'=>$selectedCitys,'channelUrl'=>$channelUrl)); ?>