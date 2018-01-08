<?php
$this->breadcrumbs=array(
	'资源'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增资源</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>