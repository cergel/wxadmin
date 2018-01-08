<?php
$this->breadcrumbs=array(
	'尿点'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>尿点管理</h1>
    <a class="btn btn-success" href="/pee/create">创建尿点</a>
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
                'width' => 60
            )
		),
		array(
				'name' => 'movie_name',
				'htmlOptions' => array(
						'width' => 80
				)
		),
			array(
					'name' => 'open_id',
					'htmlOptions' => array(
							'width' => 60
					)
			),
			array(
					'name' => 'nick_name',
					'htmlOptions' => array(
							'width' => 80
					)
			),
		array(
				'name' => 'is_pee',
			'value' => '$data->is_pee?"有":"无"',
			'filter'=> CHtml::activeDropDownList($model, 'is_pee', (['' => '全部','1'=>'有尿点','0'=>'无尿点'])),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
			'name' => 'pee_num',
            'htmlOptions' => array(
                'width' => 40
            )
		),
		array(
			'name' => 'eggs',
            'htmlOptions' => array(
				'width' => 300
            )
		),
		array(
			'name' => 'status',
			'value' => '$data->status?"上线":"下线"',
			'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','1'=>'上线','0'=>'下线'])),
			'htmlOptions' => array(
				'width' => 60
			)
		),
		array(
			'name' => 'created',
            'value' => 'date("Y-m-d H:i:s", $data->created)',
            'htmlOptions' => array(
                'width' => 120
            )
		),
		array(
			'name' => 'updated',
            'value' => 'date("Y-m-d H:i:s", $data->updated)',
            'htmlOptions' => array(
                'width' => 120
            )
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update}  {delete}'
		),
	),
)); ?>
    </div>
</div>
