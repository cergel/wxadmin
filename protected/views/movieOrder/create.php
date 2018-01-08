<?php
$this->breadcrumbs=array(
	'Movie Orders'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增MovieOrder</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>