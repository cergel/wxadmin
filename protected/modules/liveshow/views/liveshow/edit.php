<?php
$this->breadcrumbs=array(
	'明星直播'=>array('index'),
	'新建直播',
);
?>
<div class="page-header">
    <h1>编辑直播</h1>
</div>
<?php $this->renderPartial('_form_edit', array('model'=>$model)); ?>
