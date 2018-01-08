<?php
/* @var $this MovieOrderController */
/* @var $model MovieOrder */

$this->breadcrumbs=array(
	'Movie Orders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update MovieOrder <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>