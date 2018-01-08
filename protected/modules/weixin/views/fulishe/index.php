<?php
$this->breadcrumbs=array(
    '手Q'=>array('default/index'),
	'福利社'
);
?>
<div class="page-header">
    <h1>管理福利社</h1>
    <a class="btn btn-success" href="/weixin/Fulishe/create">创建福利社内容</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'fulishe-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'htmlOptions' => array(
        //'class' => 'items table table-striped table-bordered table-hover dataTable'
    ),
    'columns'=>array(
        array(
            'name' => 'iId',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'sContent',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
        array(
            'name'=>'sText',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name'=>'iTag',
            'value' => 'Fulishe::getTagList($data->iTag)',
            'filter'=> CHtml::activeDropDownList($model, 'iTag', (Fulishe::getTagList('all'))),
            'htmlOptions' => array(
                'width' => 120
            )
        ),
        array(
            'name'=>'iStatus',
            'value' => '$data->iStatus?"上线":"下线"',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', ([''=>'全部','0'=>'下线','1'=>'上线'])),
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name'=>'iOrder',
            'htmlOptions' => array(
                'width' => 50
            )
        ),
        array(
            'name'=>'onLineTime',
            'htmlOptions' => array(
                'width' =>150
            )
        ),
        array(
            'name'=>'offLineTime',
            'htmlOptions' => array(
                'width' =>150
            )
        ),
        array(
            'name'=>'iCreated',
            'value'=>'date("Y-m-d", $data->iCreated)',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
    	array(
    		'name'=>'iUpdated',
    		'value'=>'date("Y-m-d", $data->iUpdated)',
    		'htmlOptions' => array(
    				'width' => 150
    		)
    	),
        array(
            'header' => '操作',
            'class'=>'CButtonColumn',
            'headerHtmlOptions' => array(
                
            ),
            'template'=>' {update} {delete}',
            'htmlOptions' => array(
                'width' => 100
            )
        ),
	),
)); ?>
    </div>
</div>