<?php
/* @var $this MovieOrderController */
/* @var $model MovieOrder */

$this->breadcrumbs=array(
	'微信发现导流'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'更新',
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>