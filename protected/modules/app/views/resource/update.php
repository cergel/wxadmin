<?php
$this->breadcrumbs=array(
	'资源'=>array('index'),
	//$model->iResourceID=>array('update','id'=>$model->iResourceID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑资源        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iResourceID; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>