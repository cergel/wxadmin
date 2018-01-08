<?php
$this->breadcrumbs=array(
	'首页发现模块'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理模块</h1>
    <a class="btn btn-success" href="/app/discoverymodule/create">创建发现模块</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'version-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'Module_Id',
			'htmlOptions' => array(
				'width' => 40,
			),
		),
		array(
			'name' => 'Title',
			'htmlOptions' => array(
				'width' => 40,
			),
		),
		array(
			'name' => 'Link',
			'htmlOptions' => array(
					'width' => 40,
			),
		),
		array(
			'name' => 'Pic',
			'htmlOptions' => array(
					'width' => 40,
			),
		),
		array(
				'header' => '标签',
				'name' => 'Label',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '投放平台',
				'name' => 'Platform',
				'value'=>'$data->Platform',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
			'name' => 'Status',
			'value'=>'$data->Status==1?"发布":"下线"',
			'filter'=> CHtml::activeDropDownList($model, 'Status', array('' => '全部', 0 => '下线', 1 => '发布')),
			'htmlOptions' => array(
					'width' => 40,
			),
		),
		array(
			'name' => 'Start',
            'value' => 'date("Y-m-d",strtotime($data->Start))',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'End',
            'value' => 'date("Y-m-d",strtotime($data->End))',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'Created_time',
            'value' => '$data->Created_time',			
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update} {delete}',
			'buttons' =>[
				'update' => array(
					'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', '更新模块')),
					'label' => '<i class="glyphicon glyphicon-edit"></i>',
					'imageUrl' => false,
				),
				'delete' => array(
					'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', '删除模块')),
					'label' => '<i class="glyphicon glyphicon-remove"></i>',
					'imageUrl' => false,
				),
			],
		),
	),
)); ?>
    </div>
</div>
