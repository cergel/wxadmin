<?php
$this->breadcrumbs=array(
	'微信影评管理'=>array('index'),
	'新增',
);
?>
<div class="page-header">
    <h1>新增微信影评</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>