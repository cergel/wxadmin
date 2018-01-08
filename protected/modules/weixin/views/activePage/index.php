<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
    '微信'=>array('default/index'),
	'活动模板'
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
		
		
 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false, 
				height:300, 
				width:600, 
				modal:true, //蒙层（弹出会影响页面大小） 
				title:'链接查看',
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
    <h1>管理活动模板</h1>
    <a class="btn btn-success" href="/weixin/activePage/create">创建活动模板</a>
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
            'name' => 'iActivePageID',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'sName',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
        array(
            'name' => 'sDirector',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
        array(
            'name' => 'sDirectorPhone',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
    	array(
    			'name' => 'iType',
    			'value' => '$data->iType == 1 ?"单影片":"多影片"',
    			'filter'=> CHtml::activeDropDownList($model, 'iType', (['' => '全部','1'=>'单影片','2'=>'多影片',])),
    			'htmlOptions' => array(
    					'width' => 80
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
            'value'=>'date("Y-m-d H:i:s", $data->iUpdated)',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name'=>'sUpdatedName',
        ),
        array(
            'header' => '操作',
            'class'=>'CButtonColumn',
            'headerHtmlOptions' => array(
                
            ),
            'template'=>'{view} {update} {delete}',
            'buttons'=>array(
                'view' => array(
                    'label'=>'查看',
                    //'url'=>'Yii::app()->params["active_page"]["final_url"] . "/" . $data->iActivePageID . "/index.html"',
                    'url'=>'ActivePage::model()->getDialogInfo($data->iActivePageID,$data->iType,$data->iwx,$data->iqq,$data->imobile)',
                    'options' => array(
                        //'target' => '_blank',
                    	'do' => 'dialogInfo',
                    	'value' => 'ActivePage::model()->getDialogInfo($data->iActivePageID,$data->iType,$data->iwx,$data->iqq,$data->imobile)',
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
    <div id="dialogInfo" style="display: none">stgfdrfyhdfrtdfh</div>
</div>