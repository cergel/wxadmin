<?php
$this->breadcrumbs=array(
	'Show Comments'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增ShowComment</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>