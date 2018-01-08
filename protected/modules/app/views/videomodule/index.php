<?php
$this->breadcrumbs=array(
	'预告片模块'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理模块</h1>
    <a class="btn btn-success" href="/app/videomodule/create">创建预告片模块</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'version-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'Video_Module_Id',
			'htmlOptions' => array(
				'width' => 40,
			),
		),
		array(
			'name' => 'Module_Title',
			'htmlOptions' => array(
					'width' => 40,
			),
		),
		array(
				'header' => '电影1',
				'name' => 'Video1_Movie',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '标题1',
				'name' => 'Video1_Title',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '预告片1',
				'name' => 'Video1_Vid',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '电影2',
				'name' => 'Video2_Movie',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '标题2',
				'name' => 'Video2_Title',
				'htmlOptions' => array(
						'width' => 40,
				),
		),
		array(
				'header' => '预告片2',
				'name' => 'Video2_Vid',
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
			//'filter'=> $this->widget('zii.widgets.jui.CJuiDatePicker',['language'=>'zh_cn','name'=>'End',]),
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
