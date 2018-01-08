<?php
$this->breadcrumbs=array(
	'Notices'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理Notices</h1>
    <a class="btn btn-success" href="/app/Notice/create">创建Notices</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'notice-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iId',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sTitle',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sContext',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iPushid',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sUrl',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iType',
			'value'=> 'Notice::checkVal($data->iType)',
			'filter'=> CHtml::activeDropDownList($model, 'iType', array(Notice::STATUSTYPE_0 => '开启客户端', Notice::STATUSTYPE_1 => '电影详情',Notice::STATUSTYPE_2 =>'影院详情',Notice::STATUSTYPE_3 =>'活动')),
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		/*
		array(
			'name' => 'isdel',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'issend',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sendtime',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		*/
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
		),
	),
)); ?>
    </div>
</div>
