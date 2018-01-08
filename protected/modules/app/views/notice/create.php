<?php
$this->breadcrumbs=array(
	'Notices'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增Notice</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>