<?php
$this->breadcrumbs=array(
	'敏感词管理'=>array('index'),
	'新增',
);
?>
<div class="page-header">
    <h1>新增敏感词</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>