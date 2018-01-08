<?php
/* @var $this FilmFestivalController */
/* @var $model FilmFestival */

$this->breadcrumbs=array(
	'电影节管理'=>array('index'),
	'更新',
);
?>
<h1>更新电影节<?php echo $model->id; ?></h1>
<?php $this->renderPartial('_form', array('model'=>$model,'singleChip' => $singleChip, 'cinemaList' => $cinemaList, 'movieList'=>$movieList ,'screeningUnit' => $screeningUnit)); ?>

