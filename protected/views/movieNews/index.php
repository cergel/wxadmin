<?php
$this->breadcrumbs=array(
	'首页推荐'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.newsstatus', function(){
        var id = $(this).attr('movienewsId');
        $.ajax({
            url : '/movieNews/_status_update?id='+id,
            type: 'get',
            success: function (data) {
                if(data == '1') {
                    $('a.newsstatus[movienewsId='+id+']').html('上线');
                } else {
                    $('a.newsstatus[movienewsId='+id+']').html('下线');
                }
            }
        });
    });
");
?>
<div class="page-header">
    <h1>首页推荐</h1>
    <a class="btn btn-success" href="/movieNews/create">创建首页推荐</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'ad-grid',
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
			'name' => 'movie_name',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
        array(
            'name' => 'new_type',
            'value' => 'MovieNews::model()->getNewTypeList($data->new_type)',
            'filter'=> CHtml::activeDropDownList($model, 'new_type', ($model->getNewTypeList('all'))),
            'type'=>'raw',
            'htmlOptions' => array(
                'width' => 80,
            ),
        ),
		array(
			'name' => 'title',
			'htmlOptions' => array(
				'width' => 150,
			),
		),
        array(
            'name' => 'channel',
            'value' => 'MovieNews::model()->getChannelStr($data->id)',
            'filter' => '本字段暂不支持搜索',
            'htmlOptions' => array(
                'width' => 270
            )
        ),
		array(
			'name' => 'start_time',
            'value' => 'date("Y-m-d H:i:s", $data->start_time)',
            'filter' => '',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
		array(
			'name' => 'end_time',
            'value' => 'date("Y-m-d H:i:s", $data->end_time)',
            'filter' => '',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" movienewsId="\'.$data->id.\'" class="newsstatus">\'.($data->status ? "上线" : "下线")',
            'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', '1'=>'上线', '0' => '下线')),
            'htmlOptions' => array(
                'width' => 60
            )
        ),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'150'),
            'template' => '{update} {delete}',
		),
	),
)); ?>
    </div>
</div>
