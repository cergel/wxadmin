<?php
$this->breadcrumbs=array(
	'福利频道'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>创建发现</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'selectedCitys'=>$selectedCitys)); ?>