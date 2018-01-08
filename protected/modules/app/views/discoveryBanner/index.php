<?php
$this->breadcrumbs=array(
	'发现频道'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.discovery-banner-sort', function(){
        $(this).select();
    }).on('change', '.discovery-banner-sort', function(){
        $.ajax({
            url : '/app/discoveryBanner/_sort_update?id='+$(this).attr('discovery-banner'),
            type: 'post',
            data: 'sort='+$(this).val(),
            dataType: 'text',
            beforeSend: function () {
            },
            success: function () {
                $.fn.yiiGridView.update('discovery-banner-grid');
            }
        });
    });
    $(document).on('click', '.discovery-banner-status', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/app/discoveryBanner/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('上线');
                } else {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('预上线');
                }
            }
        });
    });
    $(document).on('click', '.discovery-banner-top', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/app/discoveryBanner/_top_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.discovery-banner-top[discovery-banner='+id+']').html('是');
                } else {
                    $('a.discovery-banner-top[discovery-banner='+id+']').html('否');
                }
            }
        });
    });
");
?>
<div class="page-header">
    <h1>管理活动</h1>
    <a class="btn btn-success" href="/app/discoveryBanner/create">创建活动</a>
    
</div>

<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'discovery-banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iBannerID',
            'htmlOptions' => array(
                'width'   => 50
            )
		),
		array(
			'name' => 'iType',
            'value'=>'$data->getTypeName()',
            'filter'=> CHtml::activeDropDownList($model, 'iType', (['' => '全部']+ DiscoveryBanner::getTypeList())),
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'sTitle',
            'htmlOptions' => array(
                'width' => 270
            )
		),
		array(
			'name' => 'iUserUpCount',
			'htmlOptions' => array(
			'width' => 80
			)
			),
		array(
			'name' => 'iCategory',
            'value'=>'$data->getCategoryName()',
            'filter'=> CHtml::activeDropDownList($model, 'iCategory', (array('' => '全部')+ DiscoveryBanner::getCategoryList())),
            'htmlOptions' => array(
                'width' => 100
            )
		),
        array(
            'name' => 'iStartAt',
            'htmlOptions' => array(
                'width' => 100
            )
        ),
        array(
            'name' => 'iEndAt',
            'htmlOptions' => array(
                'width' => 100
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
            'value'=>'\'<a href="javascript:void(0);" discovery-banner="\'.$data->iBannerID.\'" class="discovery-banner-status">\'.($data->iStatus ? "上线" : "预上线")',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'上线', '0' => '预上线')),
            'htmlOptions' => array(
                'width' => 70
            )
        ),
        array(
            'name' => 'iTop',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" discovery-banner="\'.$data->iBannerID.\'" class="discovery-banner-top">\'.($data->iTop ? "是" : "否")',
            'filter'=> CHtml::activeDropDownList($model, 'iTop', array('' => '全部', '1'=>'是', '0' => '否')),
            'htmlOptions' => array(
                'width' => 70
            )
        ),
        array(
            'name' => 'iSort',
            'value'=>'\'<input type="text" value="\'.$data->iSort.\'" discovery-banner="\'.$data->iBannerID.\'" class="discovery-banner-sort" />\'',
            'type' => 'raw',
            'htmlOptions' => array(
                'width' => 40,
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
