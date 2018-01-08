<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'内容',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){


 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false,
				height:500,
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
    <h1>管理内容</h1>
    <a class="btn btn-success" href="/cms/create">创建内容</a>
</div>
<div class="row">
    <div class="col-xs-12">
 <?php $this->widget('application.components.WxGridView', array(
        	'id'=>'active-grid',
        	'dataProvider'=>$model->search(),
        	'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'iActive_id',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'iType',
                    'filter'=> CHtml::activeDropDownList($model, 'iType', $model->getIType('all')),
                    'value'=>'ActiveCms::model()->getIType($data->iType)',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'sTitle',
                    'htmlOptions' => array(
                        'width' => 380,
                    ),
                ),
                array(
                    'name'=>'sSource_name',
                    'htmlOptions' => array(
                        'width' => 380,
                    ),
                ),
                array(
                    'name'=>'iIsonline',
                    'filter'=> CHtml::activeDropDownList($model, 'iIsonline', [''=>'全部','1'=>'上线','0'=>'下线 ']),
                    'value'=>'$data->iIsonline?"上线":"下线"',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'iReads',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'iLikes',
                    'htmlOptions' => array(
                        'width' => 60,
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
                            'url'=>'ActiveCms::model()->getDialogInfo($data->iActive_id)',
                            'options' => array(
                              'do' => 'dialogInfo',
                            'value' => 'Active::model()->getDialogInfo($data->iActive_id)',
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
        $(document).on("click", '.classIsOnline', function () {
            var l = $(this);
            l.text("操作中...");
            var activeId = $(this).parent().children(".class_active_id").html();
            $.ajax({
                url: '/active/activeOnlineOffline?activeId=' + activeId,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.succ == 1) {
                        if (data.online == 1) {
                            l.text("上线");
                        } else {
                            l.text("待发布");
                        }
                    } else {
                        alert(data.msg);
                    }
                },
                error: function (msg) {
                    alert(msg);
                    alert('网络异常请重试')
                }
            });
        })


        $(document).on('mouseover', '.classIsOnline', function () {
            $(this).css("color", "#2a6496");
            $(this).css("cursor", "pointer");
        })


        $(document).on('mouseout', '.classIsOnline', function () {
            $(this).css("color", "#428bca");
        })
    })
</script>