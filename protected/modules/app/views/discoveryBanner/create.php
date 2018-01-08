<?php
$this->breadcrumbs=array(
	'活动'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增活动</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>