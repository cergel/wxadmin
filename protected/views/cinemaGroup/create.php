<?php
$this->breadcrumbs=array(
	'影院分组'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增影院分组</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas)); ?>