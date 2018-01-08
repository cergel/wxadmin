<?php
$this->breadcrumbs=array(
	'广告'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增广告</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas, 'selectedMovies' => $selectedMovies)); ?>