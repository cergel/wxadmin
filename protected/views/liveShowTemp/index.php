<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'直播预热',
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
    <h1>管理直播预热页</h1>
    <a class="btn btn-success" href="/liveShowTemp/create">新建预热页</a>
</div>


<div class="row">
    <div class="col-xs-12">
 <?php $this->widget('application.components.WxGridView', array(
        	'id'=>'active-grid',
        	'dataProvider'=>$model->search(),
        	'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'title',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name'=>'link',
                    'filter'=> '',
                    'type' => 'raw',
                    'value'=>'LiveShowTemp::model()->getDialogInfo($data->id)',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{view} {update} {delete}',
                    'buttons'=>array(
                        'view' => array(
                            'label'=>'查看',
                            'url'=>'LiveShowTemp::model()->getDialogInfo($data->id)',
                            'options' => array(
                              'do' => 'dialogInfo',
                            'value' => 'LiveShowTemp::model()->getDialogInfo($data->id)',
                            )
                        ),
                    )
                ),
            ),
        )); ?>
    </div>
    <div id="dialogInfo" style="display:none">111</div>
</div>
<script>
    $(function(){
        $(document).on('mouseover', '.classIsOnline', function () {
            $(this).css("color", "#2a6496");
            $(this).css("cursor", "pointer");
        })


        $(document).on('mouseout', '.classIsOnline', function () {
            $(this).css("color", "#428bca");
        })
    })
</script>