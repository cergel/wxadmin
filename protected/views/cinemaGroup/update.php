<?php
$this->breadcrumbs=array(
	'影城分组'=>array('index'),
    $model->sName=>array('update','id'=>$model->iGroupID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑影院分组        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iGroupID; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas)); ?>