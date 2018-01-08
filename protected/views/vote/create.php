<?php
$this->breadcrumbs=array(
	'Votes'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增Vote</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'sharePlatform'=>$sharePlatform)); ?>