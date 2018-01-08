<?php
$this->breadcrumbs=array(
	'头条频道'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.discovery-banner-status', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/weixin/discoveryHead/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
                //$('a.discovery-banner-top[discovery-banner='+id+']').attr('disabled', true);
            },
            success: function (data) {
                if(data == '1') {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('上线');
                } else {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('下线');
                }
                //$('a.discovery-banner-top[discovery-banner='+id+']').removeAttr('disabled');
            }
        });
    });
");
?>
<div class="page-header">
    <h1>管理头条</h1>
    <a class="btn btn-success" href="/weixin/discoveryHead/create">创建头条</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'discovery-banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iHeadId',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'iType',
            'value'=>'DiscoveryHead::model()->getType($data->iType)',
            'filter'=> CHtml::activeDropDownList($model, 'iType', $model->getType(111)),
            'htmlOptions' => array(
                'width' => 80
            )
		),
        array(
            'name' => 'iChannel',
            'value'=>'DiscoveryHead::model()->getChannelList($data->iChannel)',
            'filter'=> CHtml::activeDropDownList($model, 'iChannel', $model->getChannelList('all')),
            'htmlOptions' => array(
                'width' => 80
            )
        ),
		array(
				'name' => 'iTypeStatus',
				'value'=>'DiscoveryHead::model()->getTypeStatus($data->iTypeStatus)',
				'filter'=> CHtml::activeDropDownList($model, 'iTypeStatus', $model->getTypeStatus(111)),
				'htmlOptions' => array(
						'width' => 80
				)
		),
		array(
			'name' => 'sTitle',
            'htmlOptions' => array(
                //'width' => 80
            )
		),
		array(
				'name' => 'sSecondTitle',
				'htmlOptions' => array(
						//'width' => 80
				)
		),
		array(
				'name' => 'sDescription',
				'htmlOptions' => array(
						//'width' => 80
				)
		),
        array(
            'name' => 'iShowAt',
            'htmlOptions' => array(
                'width' => 100
            )
        ),
        array(
            'name' => 'iHideAt',
            'htmlOptions' => array(
                'width' => 100
            )
        ),
        array(
            'name' => 'iStatus',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" discovery-banner="\'.$data->iHeadId.\'" class="discovery-banner-status">\'.($data->iStatus ? "上线" : "下线")',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'上线', '0' => '下线')),
            'htmlOptions' => array(
                'width' => 60
            )
        ),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'template'=>'{update} {delete}',
            'headerHtmlOptions' => array('width'=>'80'),
		),
	),
)); ?>
    </div>
</div>
