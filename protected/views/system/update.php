<?php
$this->breadcrumbs=array(
	'系统配置'=>array('index'),
	//$model->id=>array('update','id'=>$model->id),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        编辑系统配置       <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->key_name; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>