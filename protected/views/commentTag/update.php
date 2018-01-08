<?php
$this->breadcrumbs = array(
	'管理' => array('index'),
	'更新',
);
?>
	<div class="page-header">
		<h1>更新标签 #<?php echo $model->id; ?></h1>
	</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>