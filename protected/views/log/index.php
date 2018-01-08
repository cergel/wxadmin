<?php
/* @var $this LogController */
/* @var $model Log */

$this->breadcrumbs=array(
	'操作日志'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="page-header">
    <h1>操作日志</h1>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'iLogID',
            'htmlOptions' => array(
                'width' => 80
            ),
        ),
        array(
            'name' => 'iUserID',
            'htmlOptions' => array(
                'width' => 80
            ),
        ),
        array(
            'name' => 'sUri',
            'htmlOptions' => array(
                //'width' => 200
            ),
        ),
        array(
            'name' => 'sIp',
            'htmlOptions' => array(
                'width' => 100
            ),
        ),
        array(
            'name'=>'iCreated',
            'value'=>'date("Y-m-d H:i:s", $data->iCreated)',
            'htmlOptions' => array(
                'width' => 150
            ),
        ),
		array(
            'header' => '操作',
            'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'50'),
            'template'=>'{view}'
		),
	),
)); ?>
    </div>
</div>