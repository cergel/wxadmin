<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'标签',
);
?>

<div class="page-header">
    <h1>管理用户标签</h1>
    <a class="btn btn-success" href="/userTag/create">创建用户标签</a>
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
//                array(
//                    'name'=>'mobileNo',
//                    'htmlOptions' => array(
//                        'width' => 60,
//                    ),
//                ),
                array(
                    'name'=>'openId',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name'=>'nickname',
                    'htmlOptions' => array(
                        'width' => 120,
                    ),
                ),
                array(
                    'name'=>'tag',
                    'filter'=> '',
                    'value'=>'UserTag::model()->getUserTagByArray($data->tag)',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name'=>'summary',
                    'htmlOptions' => array(
                        'width' => 380,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{update} {delete}',
                    'buttons'=>array(
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