<?php
$this->breadcrumbs=array(
	'发现'=>array('index?type='.(!empty($_GET['type'])?$_GET['type']:'')),
	'新建',
);
?>
<div class="page-header">
    <h1>创建发现</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,)); ?>