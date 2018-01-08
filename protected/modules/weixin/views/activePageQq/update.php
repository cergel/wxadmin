<?php
$this->breadcrumbs=array(
    '微信'=>array('default/index'),
	'手Q红包模板'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑活动模板
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>