<?php
$this->breadcrumbs=array(
	'Luck Activities'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
	$(document).on('click', '.banner-status', function(){
        var id = $(this).attr('banner');
        $.ajax({
            url : '/tool/LuckActivity/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.banner-status[banner='+id+']').html('发布');
                } else {
                    $('a.banner-status[banner='+id+']').html('下线');
                }
            }
        });
    });
");
?>
<div class="page-header">
    <h1>管理Luck Activities</h1>
    <a class="btn btn-success" href="/tool/LuckActivity/create">创建Luck Activities</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'luck-activity-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iId',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
			/**
		array(
			'name' => 'iActivityType',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		*/
			
		array(
			'name' => 'sTitle',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sRule',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
			/**
		array(
			'name' => 'sDescription',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		*/
		array(
			'name' => 'iStatusTime',
			'htmlOptions' => array(
				'width' => 80,
			),
		),

		array(
				'name' => 'iEndTime',
				'htmlOptions' => array(
						'width' => 80,
				),
		),
		array(
				'name' => 'iStatus',
				'type' => 'raw',
				'value'=>'\'<a href="javascript:void(0);" banner="\'.$data->iId.\'" class="banner-status">\'.($data->iStatus ? "发布" : "下线")',
				'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'发布', '0' => '下线')),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		
		/*
		array(
			'name' => 'iDayNum',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iUpdateTime',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iLotteryNum',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sGaCode',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iLikeImage',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iGeneral',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iTickets',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iTicketsNum',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'iRequirement',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sShareTitle',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sShareDescript',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sRingTitle',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sShareImage',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		*/
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
			'template'=>'{detail} | {update} | {delete}',
            'headerHtmlOptions' => array('width'=>'80'),
			'buttons'=>array
			(
					'detail' => array
					(
							'label'=>'查看详情',
							'url'=>'Yii::app()->createUrl("tool/LuckActivity/detail", array("id"=>$data->iId))',
					),
			),
			
		),
	),
)); ?>
    </div>
</div>
