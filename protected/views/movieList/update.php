<?php
/* @var $this MovieListController */
/* @var $model MovieList */

$this->breadcrumbs=array(
	'Movie Lists'=>array('index'),
	$model->title=>array('update','id'=>$model->id),
	'Update',
);

?>

<h1>Update MovieList <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'arrMovies' => $arrMovies)); ?>