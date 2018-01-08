<?php
/* @var $this ActiveController */
/* @var $dataProvider CActiveDataProvider */
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'Actives',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){


 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false,
				height:400,
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

//$this->menu=array(
//	array('label'=>'Create Active', 'url'=>array('create')),
//	array('label'=>'Manage Active', 'url'=>array('admin')),
//);
?>

<div class="page-header">
    <h1>管理活动</h1>
    <a class="btn btn-success" href="/active/create">创建活动</a>
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
                        'width' => 80,
                        'class'=>'class_active_id',
                    ),
                ),
                array(
                    'name'=>'iType',
                    'filter'=> CHtml::activeDropDownList($model, 'iType', $model->getIType('all')),
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'Active::model()->getIType($data->iType)',
                ),
                array(
                    'name'=>'sTitle',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name'=>'iOnline_time',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'Active::model()->int2date($data->iOnline_time)',
                ),
/*                array(
                    'name'=>'iOffline_time',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'Active::model()->int2date($data->iOffline_time)',
                ),*/
                array(
                    'name'=>'iIsonline',
                    'filter'=> CHtml::activeDropDownList($model, 'iIsonline', [''=>'全部','1'=>'上线','0'=>'下线 ']),
                    'htmlOptions' => array(
                        'width' => 80,
                        'class'=>'classIsOnline',
                        'style'=>'color: #428bca;text-decoration: none;',
                    ),
                    'value'=>'Active::model()->getOnlineStatus($data->iIsonline)',
                    ),
                array(
                    'name'=>'iReads',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->getRealReadNum($data->iActive_id)',
                ),
                array(
                    'name'=>'iLikes',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->getRealLikesNum($data->iActive_id)',
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{view} {edit} {release} {delete}',
                    'buttons'=>array(
                        'view' => array(
                            'label'=>'查看',
                           // 'url'=>'Yii::app()->params["active"]["final_url"] . "/" . $data->iActive_id . "/index.html"',
                            'url'=>'Active::model()->getDialogInfo($data->iActive_id)',
                            'options' => array(
                               //'target' => '_blank',
                              'do' => 'dialogInfo',
                            'value' => 'Active::model()->getDialogInfo($data->iActive_id)',
                            )
                        ),
                        'edit'=>array(
                            'label'=>'编辑',
                            'url'=>'Active::model()->getEditUrl($data->iActive_id)'
                        ),
                        'release'=>array(
                            'label'=>'发布',
                            'url'=>'Active::model()->getReleaseUrl($data->iActive_id)'
                        )
                    )
                ),
            ),
        )); ?>
    </div>
    <div id="dialogInfo" style="display:none">stgfdrfyhdfrtdfh</div>
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