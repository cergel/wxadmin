<?php
$this->breadcrumbs=array(
	'内容'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>创建内容</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'selectedCities'=>$selectedCities,'isCreate'=>1)); ?>