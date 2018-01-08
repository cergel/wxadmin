<?php
/* @var $this MessageNoticeController */
/* @var $model MessageNotice */

$this->breadcrumbs=array(
	'消息中心'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>消息更新 <?php echo $model->id; ?></h1>
<?php $this->renderPartial('_form', array('model'=>$model,'channelUrl' => $channelUrl, 'bool' => $bool)); ?>
