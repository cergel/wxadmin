<?php
$this->breadcrumbs=array(
	'Actors'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增Actor</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'actor_head'=>'')); ?>