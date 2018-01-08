<?php
/* @var $this CinemaNotificationController */
/* @var $model CinemaNotification */

$this->breadcrumbs=array(
	'影城公告'=>array('index'),
	$model->sName=>array('update','id'=>$model->iNotificationID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑影城公告
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo $model->iNotificationID; ?>
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas)); ?>