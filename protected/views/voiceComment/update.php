<?php

$this->breadcrumbs=array(
	'主创说'=>array('index'),
	'Update',
);

?>
	<div class="page-header">
		<h1>
			编辑主创说
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				#<?php echo $model->id; ?></small>
		</h1>
	</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>