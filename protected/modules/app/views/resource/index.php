<?php
$this->breadcrumbs=array(
	'资源'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理资源</h1>
    <a class="btn btn-success" href="/app/resource/create">创建资源</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'resource-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'iResourceID',
            'htmlOptions' => array(
                'width' => 80,
            ),
        ),
        array(
            'name' => 'iChannelID',
            'value'=>'$data->iChannelID==Resource::CHANNEL_IOS?"IOS":($data->iChannelID==Resource::CHANNEL_ANDROID?"Android":"Error")',
            'filter'=> CHtml::activeDropDownList($model, 'iChannelID', array('' => '全部', Resource::CHANNEL_IOS => 'IOS', Resource::CHANNEL_ANDROID => 'Android')),
            'htmlOptions' => array(
                'width' => 80,
            ),
        ),
		array(
			'name' => 'sName',
			'htmlOptions' => array(
				//'width' => 80,
			),
		),
		array(
			'name' => 'sNote',
			'htmlOptions' => array(
				//'width' => 80,
			),
		),
        array(
            'name' => 'sPath',
            'htmlOptions' => array(
                'width' => 80,
            ),
        ),
		array(
			'name' => 'iLastModified',
            'value' => 'date("Y-m-d H:i:s", $data->iLastModified)',
			'htmlOptions' => array(
				'width' => 150,
			),
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template'=>'{update} {delete}',
		),
	),
)); ?>
    </div>
</div>
