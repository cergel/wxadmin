<?php
$this->breadcrumbs=array(
	'版本'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增版本</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>