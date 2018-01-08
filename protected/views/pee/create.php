<?php
$this->breadcrumbs=array(
	'尿点'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增尿点</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>