<?php
/* @var $this RecommendWillController */
/* @var $model RecommendWill */

$this->breadcrumbs=array(
	'Recommend Wills'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>
<div class="page-header">
    <h1>新增RecommendWill</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>