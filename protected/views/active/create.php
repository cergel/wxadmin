<?php
$this->breadcrumbs=array(
	'Actives'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增Active</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'selectedCities'=>$selectedCities,'updateType'=>$updateType,'isCreate'=>1)); ?>