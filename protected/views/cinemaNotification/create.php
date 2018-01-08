<?php
/* @var $this CinemaNotificationController */
/* @var $model CinemaNotification */

$this->breadcrumbs=array(
	'影城公告'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新建影城公告</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas)); ?>