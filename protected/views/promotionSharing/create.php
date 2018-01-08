<?php
$this->breadcrumbs=array(
	'拉新分享管理'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增拉新分享</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>