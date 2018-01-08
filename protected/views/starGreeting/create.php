<?php
$this->breadcrumbs=array(
	'管理'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增明星问候</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>