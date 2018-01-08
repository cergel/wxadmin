<?php
/* @var $this MovieListRelationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Movie List Relations',
);

$this->menu=array(
	array('label'=>'Create MovieListRelation', 'url'=>array('create')),
	array('label'=>'Manage MovieListRelation', 'url'=>array('admin')),
);
?>

<h1>Movie List Relations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
