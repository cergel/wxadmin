<?php
$this->breadcrumbs=array(
	'首页推荐'=>array('index'),
	//$model->iAdID=>array('update','id'=>$model->iAdID),
	'编辑',
);
?>
<div class="page-header">
    <h1>编辑首页推荐        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities,'channelUrl'=>$channelUrl)); ?>