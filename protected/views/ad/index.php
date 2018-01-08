<?php
$this->breadcrumbs=array(
	'广告'=>array('index'),
	'管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.ad-status', function(){
        var id = $(this).attr('ad');
        $.ajax({
            url : '/ad/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.ad-status[ad='+id+']').html('上线');
                } else {
                    $('a.ad-status[ad='+id+']').html('预上线');
                }
            }
        });
    });
");
?>
<div class="page-header">
    <h1>管理广告</h1>
    <a class="btn btn-success" href="/Ad/create">创建广告</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'ad-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iAdID',
			'htmlOptions' => array(
				'width' => 80,
			),
		),
		array(
			'name' => 'sTitle',
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
			'name' => 'sLink',
			'htmlOptions' => array(
				'width' => 150,
			),
		),
        array(
            'name' => 'iType',
            'value'=>'$data->getTypeName()',
            'filter'=> CHtml::activeDropDownList($model, 'iType', array_merge(array('' => '全部'), Ad::getTypeList())),
            'htmlOptions' => array(
                'width' => 120
            )
        ),
		array(
			'name' => 'iShowAt',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
		array(
			'name' => 'iHideAt',
			'htmlOptions' => array(
				'width' => 100,
			),
		),
        array(
            'name' => 'iStatus',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" ad="\'.$data->iAdID.\'" class="ad-status">\'.($data->iStatus ? "上线" : "预上线")',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'上线', '0' => '预上线')),
            'htmlOptions' => array(
                'width' => 60
            )
        ),
        array(
            'name' => 'iCreated',
            'value' => 'date("Y-m-d H:i:s", $data->iCreated)',
            'htmlOptions' => array(
                'width' => 100
            )
        ),
        array(
            'name' => 'iUpdated',
            'value' => 'date("Y-m-d H:i:s", $data->iUpdated)',
            'htmlOptions' => array(
                'width' => 100
            ),
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
