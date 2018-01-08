<?php
/* @var $this VoteController */
/* @var $model Vote */

$this->breadcrumbs=array(
	'Votes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Update Vote <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_formupdate', array(
    'model'=>$model,
    'sharePlatForm'=>$sharePlatform,
    'readOnly'=>$readOnly,
    'arrAnswerInfo'=>$arrAnswerInfo,
    'arrCheckedSharePlatform'=>$arrCheckedSharePlatform,
    'arrMovies'=>$arrMovies,
)); ?>