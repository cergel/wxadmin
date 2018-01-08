<?php
$this->breadcrumbs=array(
	'Luck Activities'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增LuckActivity</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>