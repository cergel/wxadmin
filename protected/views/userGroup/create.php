<?php
$this->breadcrumbs=array(
	'用户权限分组' => array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增权限分组</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>