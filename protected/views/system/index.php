<?php
$this->breadcrumbs=array(
	'系统配置'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理系统配置</h1>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'movie-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'id',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
				'name' => 'key_name',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
				'name' => 'start_value',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
			'name' => 'end_value',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'content',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'status',
			'value' => 'System::model()->getStatus($data->status)',
			'filter'=> CHtml::activeDropDownList($model, 'status', $model->getStatus('all')),
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'stype',
			'value' => 'System::model()->getStype($data->stype)',
			'filter'=> CHtml::activeDropDownList($model, 'stype', $model->getStype('all')),
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'updated',
            'value' => 'date("Y-m-d H:i:s", $data->updated)',
            'htmlOptions' => array(
                'width' => 150
            )
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update}'
		),
	),
)); ?>
    </div>
</div>
