<?php
$this->breadcrumbs=array(
	'广告'=>array('index'),
	//$model->iAdID=>array('update','id'=>$model->iAdID),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑广告        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iAdID; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCinemas' => $selectedCinemas, 'selectedMovies' => $selectedMovies)); ?>