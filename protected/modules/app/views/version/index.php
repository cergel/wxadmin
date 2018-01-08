<?php
$this->breadcrumbs=array(
	'版本'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理版本</h1>
    <a class="btn btn-success" href="/app/version/create">创建版本</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'version-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'id',
			'htmlOptions' => array(
				'width' => 40,
			),
		),
		array(
			'name' => 'title',
			'htmlOptions' => array(
					'width' => 80,
			),
		),
		array(
			'name' => 'itype',
			'value'=>'$data->itype==8?"IOS":"Android"',
			'filter'=> CHtml::activeDropDownList($model, 'itype', array('' => '全部', 8 => 'IOS', 9 => 'Android')),
			'htmlOptions' => array(
					'width' => 60,
			),
		),
		array(
			'name' => 'status',
			'value'=>'$data->status==1?"发布":"下线"',
			'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', 0 => '下线', 9 => '发布')),
			'htmlOptions' => array(
					'width' => 50,
			),
		),
        array(
            'name' => 'version',
            'htmlOptions' => array(
                'width' => 60,
            ),
        ),
        array(
            'name' => 'forceUpdate',
            'value'=>'$data->forceUpdate==1?"是":"否"',
            'filter'=> CHtml::activeDropDownList($model, 'forceUpdate', array('' => '全部', 1 => '是', 0 => '否')),
            'htmlOptions' => array(
                'width' => 60,
            ),
        ),
		array(
			'name' => 'path',
			'type'=>'raw',
			'value'=>'Version::model()->getPath($data->path,$data->id,$data->itype)',
			'htmlOptions' => array(
				//'width' => 80,
			),
		),
		array(
			'name' => 'img',
			'type'=>'raw',
			'value'=>'Version::model()->getImg($data->img,$data->id)',
			'htmlOptions' => array(
					//'width' => 80,
			),
		),
		array(
			'name' => 'created',
            'value' => 'date("Y-m-d H:i:s", $data->created)',
			'htmlOptions' => array(
				'width' => 150,
			),
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update} {delete}',
		),
	),
)); ?>
    </div>
</div>
