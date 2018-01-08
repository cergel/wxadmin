<?php
$this->breadcrumbs=array(
	'标签'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>创建用户标签</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>