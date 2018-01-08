<?php
$this->breadcrumbs=array(
	'音乐方案'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增音乐方案</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>