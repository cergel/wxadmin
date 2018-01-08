<?php
$this->breadcrumbs=array(
	'片单列表'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新建片单</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>