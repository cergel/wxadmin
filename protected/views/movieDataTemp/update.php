<?php
$this->breadcrumbs=array(
	'影片库'=>array('index'),
	//$model->id=>array('update','id'=>$model->id),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑影片        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->movie_no; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>