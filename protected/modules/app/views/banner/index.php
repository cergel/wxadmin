<?php
$this->breadcrumbs=array(
	'Banner'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.banner-sort', function(){
        $(this).select();
    }).on('change', '.banner-sort', function(){
        $.ajax({
            url : '/app/banner/_sort_update?id='+$(this).attr('banner'),
            type: 'post',
            data: 'sort='+$(this).val(),
            dataType: 'text',
            beforeSend: function () {
            },
            success: function () {
                $.fn.yiiGridView.update('banner-grid');
            }
        });
    });
    $(document).on('click', '.banner-status', function(){
        var id = $(this).attr('banner');
        $.ajax({
            url : '/app/banner/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.banner-status[banner='+id+']').html('上线');
                } else {
                    $('a.banner-status[banner='+id+']').html('预上线');
                }
            }
        });
    });

");
?>
<div class="page-header">
    <h1>管理Banner</h1>
    <a class="btn btn-success" href="/app/banner/create">创建Banner</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iBannerID',
            'htmlOptions' => array(
                'width'   => 80
            )
		),
		array(
			'name' => 'sTitle',
            'htmlOptions' => array(
                //'width' => 80
            )
		),
	    array(
	        'name' => 'iIOS',
	        'type' => 'raw',
	        'value'=>'($data->iIOS ? "是" : "否")',
	        'filter'=> CHtml::activeDropDownList($model, 'iIOS', array('' => '全部', '0'=>'否', '1' => '是')),
	        'htmlOptions' => array(
	            'width' => 50
	        )
	    ),
	    array(
	        'name' => 'iAndroid',
	        'type' => 'raw',
	        'value'=>'($data->iAndroid ? "是" : "否")',
	        'filter'=> CHtml::activeDropDownList($model, 'iAndroid', array('' => '全部', '0'=>'否', '1' => '是')),
	        'htmlOptions' => array(
	            'width' => 50
	        )
	    ),
	    array(
	        'name' => 'iWX',
	        'type' => 'raw',
	        'value'=>'($data->iWX ? "是" : "否")',
	        'filter'=> CHtml::activeDropDownList($model, 'iWX', array('' => '全部', '0'=>'否', '1' => '是')),
	        'htmlOptions' => array(
	            'width' => 50
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
            'value'=>'\'<a href="javascript:void(0);" banner="\'.$data->iBannerID.\'" class="banner-status">\'.($data->iStatus ? "上线" : "预上线")',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'上线', '0' => '预上线')),
            'htmlOptions' => array(
                'width' => 60
            )
        ),
        array(
            'name' => 'iSort',
            'value'=>'\'<input type="text" value="\'.$data->iSort.\'" banner="\'.$data->iBannerID.\'" class="banner-sort" />\'',
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
