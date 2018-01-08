<?php
$this->breadcrumbs=array(
	'活动'=>array('index'),
	$model->sTitle=>array('update','id'=>$model->iHeadId),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑活动        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iHeadId; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>