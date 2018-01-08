<?php
$this->breadcrumbs=array(
    '手Q'=>array('default/index'),
	'福利社'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑活动模板
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iId; ?>
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>