<?php
$this->breadcrumbs=array(
	'微信影评'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.discovery-banner-status', function(){
        var id = $(this).attr('discovery-banner');
        $.ajax({
            url : '/weixinComment/_status_update?id='+id,
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
<h1>微信影评管理</h1>
<a class="btn btn-success" href="/weixinComment/create">新增微信影评</a>
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
                'width' => 30
            )
		),
		array(
			'name' => 'commentId',
            'htmlOptions' => array(
                'width' => 40
            )
		),
		array(
				'name' => 'content',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
				'name' => 'movieId',
				'htmlOptions' => array(
						'width' => 40
				)
		),
		array(
				'name' => 'movieName',
				'htmlOptions' => array(
						'width' => 80
				)
		),
		array(
				'name' => 'uid',
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
				'name' => 'uname',
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
				'name' => 'showTime',
				'value' => 'date("Y-m-d H:i:s", $data->showTime)',
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
				'name' => 'status',
				'type' => 'raw',
				'value'=>'\'<a href="javascript:void(0);" discovery-banner="\'.$data->id.\'" class="discovery-banner-status">\'.($data->status ? "上线" : "下线")',
				'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','1'=>'上线','0'=>'下线'])),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
			'name' => 'editTime',
            'value' => 'date("Y-m-d H:i:s", $data->editTime)',
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
