<?php
$this->breadcrumbs=array(
	'Banner'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增Banner</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>