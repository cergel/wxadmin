<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'用户'=>array('index'),
	$model->sEmail=>array('update','id'=>$model->iUserID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑用户
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iUserID; ?>
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
