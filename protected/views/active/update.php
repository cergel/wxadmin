<?php
/* @var $this ActiveController */
/* @var $model Active */

$this->breadcrumbs=array(
	'Actives'=>array('index'),
	$model->iActive_id=>array('view','id'=>$model->iActive_id),
	'Update',
);


//$this->menu=array(
//	array('label'=>'List Active', 'url'=>array('index')),
//	array('label'=>'Create Active', 'url'=>array('create')),
//	array('label'=>'View Active', 'url'=>array('view', 'id'=>$model->iActive_id)),
//	array('label'=>'Manage Active', 'url'=>array('admin')),
//);

?>

<h1>Update Active <?php echo $model->iActive_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'selectedCities'=>$selectedCities,'release'=>$release, 'share'=>$share,'updateType'=>$updateType)); ?>