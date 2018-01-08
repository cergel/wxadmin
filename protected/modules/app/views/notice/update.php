<?php
$this->breadcrumbs=array(
	'Notices'=>array('index'),
	//$model->title=>array('update','id'=>$model->id),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑Notice        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iId; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>