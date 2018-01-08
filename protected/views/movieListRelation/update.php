<?php
/* @var $this MovieListRelationController */
/* @var $model MovieListRelation */

$this->breadcrumbs=array(
	'Movie List Relations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MovieListRelation', 'url'=>array('index')),
	array('label'=>'Create MovieListRelation', 'url'=>array('create')),
	array('label'=>'View MovieListRelation', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MovieListRelation', 'url'=>array('admin')),
);
?>

<h1>Update MovieListRelation <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>