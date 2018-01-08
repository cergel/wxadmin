<?php
/* @var $this CinemaNotificationController */
/* @var $model CinemaNotification */

$this->breadcrumbs=array(
	'定制化选坐'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新建定制化选座</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>