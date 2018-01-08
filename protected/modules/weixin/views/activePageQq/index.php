<?php
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
    '微信'=>array('default/index'),
	'手Q红包模板'
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
		
		
 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false, 
				height:180, 
				width:700, 
				modal:true, //蒙层（弹出会影响页面大小） 
				title:'手Q红包模板',
				overlay: {opacity: 0.5, background: 'black' ,overflow:'auto'}, 
				buttons:{ 
					'确定':function(){ 
						$(this).dialog('close');
					}
		}});
		$('#dialogInfo').dialog('open');
		
		return false;
    });
");
?>
<div class="page-header">
    <h1>管理红包模板</h1>
    <a class="btn btn-success" href="/weixin/activePageQq/create">创建红包模板</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'active-page-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'htmlOptions' => array(
        //'class' => 'items table table-striped table-bordered table-hover dataTable'
    ),
    'columns'=>array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'title',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
        array(
            'name'=>'shareTitle',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name'=>'created',
            'value'=>'date("Y-m-d", $data->created)',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
    	array(
    		'name'=>'updated',
    		'value'=>'date("Y-m-d", $data->updated)',
    		'htmlOptions' => array(
    				'width' => 150
    		)
    	),
        array(
            'header' => '操作',
            'class'=>'CButtonColumn',
            'headerHtmlOptions' => array(
                
            ),
            'template'=>'{view} {update}',
            'buttons'=>array(
                'view' => array(
                    'label'=>'查看',
                    //'url'=>'Yii::app()->params["active_page"]["final_url"] . "/" . $data->iActivePageID . "/index.html"',
                    'url'=>'ActivePageForQq::model()->getDialogInfo($data->id)',
                    'options' => array(
                        //'target' => '_blank',
                    	'do' => 'dialogInfo',
                    	'value' => 'ActivePageForQq::model()->getDialogInfo($data->id)',
                    )
                ),
            ),
            'htmlOptions' => array(
                'width' => 100
            )
        ),
	),
)); ?>
    </div>
    <div id="dialogInfo" style="display: none"></div>
</div>