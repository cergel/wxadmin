<?php
$this->breadcrumbs=array(
	'头条'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增头条</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>