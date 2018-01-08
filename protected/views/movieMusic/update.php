<?php
$this->breadcrumbs=array(
	'音乐方案管理'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑音乐方案       <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>