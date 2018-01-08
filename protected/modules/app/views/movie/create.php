<?php
$this->breadcrumbs=array(
	'影片'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增影片</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>