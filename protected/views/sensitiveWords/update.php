<?php
$this->breadcrumbs=array(
	'敏感词管理'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑敏感词        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>