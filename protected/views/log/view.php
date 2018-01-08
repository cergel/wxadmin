<?php
/* @var $this LogController */
/* @var $model Log */

$this->breadcrumbs=array(
	'操作日志'=>array('index'),
	$model->iLogID,
);
?>
<div class="page-header">
    <h1>
        查看日志
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            #<?php echo $model->iLogID; ?>
        </small>
    </h1>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iLogID',
		'iUserID',
		'sUri',
		//'sData',
        array(
            'name'=>'sData',
            'type' => 'raw',
            'value'=>"<pre>".var_export(json_decode($model->sData, TRUE), TRUE)."</pre>",
        ),
		'sIp',
        array(
            'name'=>'iCreated',
            'value'=>date("Y-m-d H:i:s", $model->iCreated),
        ),
	),
)); ?>
