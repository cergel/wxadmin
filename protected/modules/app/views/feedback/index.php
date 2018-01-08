<?php
$this->breadcrumbs=array(
	'反馈'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理反馈</h1>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'feedback-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'id',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'uid',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'mobileNo',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
		array(
			'name' => 'content',
			'htmlOptions' => array(
				//'width' => 80,
			),
		),
		array(
			'name' => 'version',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'channelId',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'fromId',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'device',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'os',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'network',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'note',
			'htmlOptions' => array(
				//'width' => 80,
			),
		),
		array(
			'name' => 'created',
            'value' => 'date("Y-m-d H:i:s", $data->created)',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
        /*
		array(
			'name' => 'updated',
            'value' => 'date("Y-m-d H:i:s", $data->updated)',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
        */
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update} {delete}'
		),
	),
)); ?>
    </div>
</div>
