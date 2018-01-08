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
            url : '/weixin/discoveryBanner/_sort_update?id='+$(this).attr('discovery-banner'),
            type: 'post',
            data: 'sort='+$(this).val(),
            dataType: 'text',
            beforeSend: function () {
                //$('a.discovery-banner-top[discovery-banner='+id+']').attr('disabled', true);
            },
            success: function () {
                //$('a.discovery-banner-top[discovery-banner='+id+']').removeAttr('disabled');
                $.fn.yiiGridView.update('discovery-banner-grid');
            }
        });
    });
    $(document).on('click', '.discovery-banner-status', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/weixin/discoveryBanner/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
                //$('a.discovery-banner-top[discovery-banner='+id+']').attr('disabled', true);
            },
            success: function (data) {
                if(data == '1') {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('上线');
                } else {
                    $('a.discovery-banner-status[discovery-banner='+id+']').html('预上线');
                }
                //$('a.discovery-banner-top[discovery-banner='+id+']').removeAttr('disabled');
            }
        });
    });
    $(document).on('click', '.discovery-banner-top', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/weixin/discoveryBanner/_top_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
                //$('a.discovery-banner-top[discovery-banner='+id+']').attr('disabled', true);
            },
            success: function (data) {
                if(data == '1') {
                    $('a.discovery-banner-top[discovery-banner='+id+']').html('是');
                } else {
                    $('a.discovery-banner-top[discovery-banner='+id+']').html('否');
                }
                //$('a.discovery-banner-top[discovery-banner='+id+']').removeAttr('disabled');
            }
        });
    });
");
?>
<div class="page-header">
    <h1>管理活动</h1>
    <a class="btn btn-success" href="/weixin/discoveryBanner/create">创建活动</a>
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
                'width' => 80
            )
		),
		array(
			'name' => 'iType',
            'value'=>'$data->iType=="2"?"活动":($data->iType=="1"?"内容":"")',
            'filter'=> CHtml::activeDropDownList($model, 'iType', array(
                '' => '全部',
                $model::TYPE_ACTIVITY => '活动',
                $model::TYPE_CONTENT => '内容'
            )),
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
			'name' => 'iCategory',
            'value'=>'$data->getCategoryName()',
            'filter'=> CHtml::activeDropDownList($model, 'iCategory', array(
                '' => '全部',
                $model::CONTENT_CATEGORY_1 => '本周约啥',
                $model::CONTENT_CATEGORY_2 => '单片推荐',
                $model::CONTENT_CATEGORY_3 => '图册-眼保健操',
                $model::CONTENT_CATEGORY_4 => '文-干货特供',
                $model::CONTENT_CATEGORY_5 => '视频-正在缓冲',
                $model::CONTENT_CATEGORY_6 => '片单',
            )),
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
                'width' => 60
            )
        ),
        array(
            'name' => 'iTop',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" discovery-banner="\'.$data->iBannerID.\'" class="discovery-banner-top">\'.($data->iTop ? "是" : "否")',
            'filter'=> CHtml::activeDropDownList($model, 'iTop', array('' => '全部', '1'=>'是', '0' => '否')),
            'htmlOptions' => array(
                'width' => 60
            )
        ),
        array(
            'name' => 'iSort',
            'value'=>'\'<input type="text" value="\'.$data->iSort.\'" discovery-banner="\'.$data->iBannerID.\'" class="discovery-banner-sort" />\'',
            'type' => 'raw',
            'htmlOptions' => array(
                'width' => 50,

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
