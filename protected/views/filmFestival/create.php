<?php
$this->breadcrumbs=array(
	'管理'=>array('index'),
	'创建',
);
?>
<div class="page-header">
    <h1>创建电影节</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>