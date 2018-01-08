<?php
$this->breadcrumbs=array(
	'App Day Pushes'=>array('index'),
	//$model->iId=>array('update','id'=>$model->iId),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑AppDayPush        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iId; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>