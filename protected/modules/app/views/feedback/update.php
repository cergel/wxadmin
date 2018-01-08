<?php
$this->breadcrumbs=array(
	'反馈'=>array('index'),
	//$model->id=>array('update','id'=>$model->id),
	'备注',
);
?>
<div class="page-header">
    <h1>
        备注        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>