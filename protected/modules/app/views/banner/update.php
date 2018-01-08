<?php
$this->breadcrumbs=array(
	'Banner'=>array('index'),
	$model->sTitle=>array('update','id'=>$model->iBannerID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑Banner        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iBannerID; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>