<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'福利频道',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
    });
");
?>

<div class="page-header">
    <h1>管理福利频道</h1>
    <a class="btn btn-success" href="/fuli/create">新增福利</a>
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
                    'name'=>'photo',
                    'filter'=>'',
                    'type' => 'raw',
                    'value'=>'\'<img  src="\'.$data->photo.\'" class="ad-status"  width="60">\'',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
//                array(
//                    'name'=>'f_type',
//                    'filter'=> CHtml::activeDropDownList($model, 'f_type', $model->getTypeList('all')),
//                    'value'=>'ActiveFind::model()->getTypeList($data->f_type)',
//                    'htmlOptions' => array(
//                        'width' => 60,
//                    ),
//                ),
                array(
                    'name'=>'title',
                    'htmlOptions' => array(
                        'width' => 380,
                    ),
                ),
                array(
                    'name' => 'time_box',
                   // 'value' => 'date("Y-m-d H:i:s", $data->up_time)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 180,
                    ),
                ),
                array(
                    'name' => 'up_time',
                    'value' => 'date("Y-m-d H:i:s", $data->up_time)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 130,
                    ),
                ),
                array(
                    'name'=>'status',
                    'filter'=> CHtml::activeDropDownList($model, 'status', [''=>'全部','1'=>'上线','0'=>'下线']),
                    'value'=>'$data->status?"上线":"下线"',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{update} {delete}',
                    'buttons'=>array()
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