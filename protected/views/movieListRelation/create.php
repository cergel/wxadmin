<?php
$this->breadcrumbs=array(
	'Movie List Relations'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增MovieListRelation</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>