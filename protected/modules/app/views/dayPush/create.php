<?php
$this->breadcrumbs=array(
	'App Day Pushes'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增AppDayPush</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>