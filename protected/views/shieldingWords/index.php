<?php
$this->breadcrumbs=array(
	'屏蔽词'=>array('index'),
	'管理',
);
?>
<div class="page-header">
<h1>屏蔽词管理</h1>
<a class="btn btn-success" href="/shieldingWords/create">新增屏蔽词</a>
</div>


<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'comment-grid',
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
			'name' => 'name',
            'htmlOptions' => array(
                'width' => 60
            )
		),
		array(
				'name' => 'stype',
				'value' => 'ShieldingWords::model()->getStype($data->stype)',
				'filter'=> CHtml::activeDropDownList($model, 'stype', $model->getStype('all')),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
			'name' => 'created',
            'value' => 'date("Y-m-d H:i:s", $data->created)',
            'htmlOptions' => array(
                'width' => 70
            )
		),
		array(
			'name' => 'updated',
            'value' => 'date("Y-m-d H:i:s", $data->updated)',
            'htmlOptions' => array(
                'width' => 70
            )
		),
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
