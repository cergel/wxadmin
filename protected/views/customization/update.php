<?php
/* @var $this CinemaNotificationController */
/* @var $model CinemaNotification */

$this->breadcrumbs=array(
	'个性化选坐'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑选坐方案
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo $model->SeatId; ?>
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form_update', array('model'=>$model)); ?>