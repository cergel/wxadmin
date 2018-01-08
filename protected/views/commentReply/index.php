<?php
$this->breadcrumbs=array(
    '回复'=>array('index'),
    '管理',
);
?>
<div class="page-header">
    <h1>管理回复</h1>
</div>

<script type="text/javascript">
    function GetCheckbox(type){
        var data=new Array();
        $("input:checkbox[name='commentId[]']").each(function (){
            if($(this).is(':checked')){
                data.push($(this).val());
            }
        });
        if(data.length > 0){
            if(type == 0){
                var url = "/commentReply/deleteAll";
            }else{
                var url = "/commentReply/statusAll";
            }
            $.post(url,{'commentId[]':data}, function (data) {
                if (data=='ok') {
                    if(type == 0){
                        alert('删除成功！');
                        $("input:checkbox[name='commentId[]']").each(function (){
                            if($(this).is(':checked')){
                                $(this).parent().parent().remove();
                            }
                        });
                    }else{
                        alert('审核成功！');
                        $("input:checkbox[name='commentId[]']").each(function (){
                            if($(this).is(':checked')){
                                $(this).parent().parent().find("td").last().prev().html("已审核");
                            }
                        });
                    }
                }else{

                }
            });
        }else{
            if(type == 0){
                alert("请选择要删除的评论!");
            }else{
                alert("请选择要通过的评论!");
            }
        }
    }

</script>

<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'comment-reply-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'selectableRows' => 2,
                    'class'=>'CCheckBoxColumn',
                    'headerHtmlOptions' => array('width'=>'18px'),
                    'checkBoxHtmlOptions' => array('name' => 'commentId[]','class'=>'myclass',), //复选框的 html 选项
                ),
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 50
                    )
                ),
                array(
                    'name' => 'commentId',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'ucid',
                    'type' => 'raw',
                    'value' => 'BlackList::model()->getUcidStr($data->ucid)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'channelId',
                    'value' => 'Comment::model()->getchannelId($data->channelId)',
                    'filter'=> CHtml::activeDropDownList($model, 'channelId', (['' => '全部','3'=>'微信电影票','8'=>'IOS','9'=>'安卓','10'=>'PC'])),
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'content',
                    'value' => 'SensitiveWords::model()->isSensitiveWordInfo($data->content)',
                    'type'=>'raw',
                    'htmlOptions' => array(
                        'width' => 400,
                    ),
                ),
                array(
                    'name' => 'favorCount',
                    'htmlOptions' => array(
                        'width' => 50
                    )
                ),
                array(
                    'name' => 'updated',
                    'value' => 'date("Ymd H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'checkstatus',
                    'value' => 'Comment::model()->getCheckstatus($data->checkstatus)',
                    'filter'=> CHtml::activeDropDownList($model, 'checkstatus', (Comment::model()->getCheckstatus('all'))),
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'80'),
                    'template' => '{delete}'
                ),
            ),
        )); ?>
    </div>
    <button type="button"  onclick="GetCheckbox(0);" style="width:76px">批量删除</button><button type="button"  onclick="GetCheckbox(1);" style="width:76px">批量通过</button>
</div>