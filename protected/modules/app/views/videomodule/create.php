<?php
$this->breadcrumbs=array(
	'预告片模块'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增预告片模块</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>