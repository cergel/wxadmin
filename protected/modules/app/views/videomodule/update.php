<?php
$this->breadcrumbs=array(
	'预告片模块'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>编辑预告片模块</h1>
</div>
<?php $this->renderPartial('_update_form', array('model'=>$model)); ?>