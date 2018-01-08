<?php
$this->breadcrumbs=array(
	'推荐'=>array('index'),
	//$model->id=>array('update','id'=>$model->id),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        <?php echo $model->isNewRecord ?  '新增': '修改';     ?> 推荐       <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->id; ?>        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'info'=>$info)); ?>