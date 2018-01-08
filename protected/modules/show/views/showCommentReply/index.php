<?php
/* @var $this ShowCommentReplyController */
/* @var $dataProvider CActiveDataProvider */
$this->breadcrumbs = array(
    '回复' => array('index'),
    '管理',

);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
?>
<div class="page-header">
    <h1>回复管理</h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'show-comment-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'selectableRows' => 2,
                    'class' => 'CCheckBoxColumn',
                    'headerHtmlOptions' => array('width' => '18px'),
                    'checkBoxHtmlOptions' => array('name' => 'commentId[]', 'class' => 'myclass',), //复选框的 html 选项
                ),
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 40,
                        'class' => 'idData'
                    )
                ),
                array(
                    'name' => 'commentId',
                    'htmlOptions' => array(
                        'width' => 40,
                        'class' => 'commentId',
                    )
                ),
                array(
                    'name' => 'content',
                    'value' => 'SensitiveWords::model()->isSensitiveWordInfo($data->content)',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'openId',
                    'htmlOptions' => array(
                        'width' => 80,
                        'class' => 'openId',
                    )
                ),
                array(
                    'name' => 'created',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'htmlOptions' => array(
                        'width' => 100
                    ),
                ),
                array(
                    'name' => 'checkstatus',
                    'filter' => CHtml::activeDropDownList($model, 'checkstatus', (['' => '全部', '0' => '不含敏感词', '1' => '含有敏感词'])),
                    'value' => '$data->checkstatus==1?"含有敏感词":"不含敏感词"',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'status_type',
                    'filter' => CHtml::activeDropDownList($model, 'status_type', (['' => '全部', '1' => '上线', '2' => '待审核'])),
                    'value' => 'ShowComment::model()->statusName($data->status_type)',
                    'htmlOptions' => array(
                        'width' => 50,
                        'class' => 'status_type',
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '120'),
                    'template' => '{shield} {pass} {detail} {mobile}',
                    'buttons' => array(
                        'shield' => array(
                            'label' => '屏蔽',
                            'options' => array('onclick' => 'editStatusType(this)', 'typeData' => 1),
                            'visible' => '$data->status_type != 0',
                            'url' => '"javascript:void(0)"'
                        ),
                        'pass' => array(
                            'label' => '通过',
                            'options' => array(),
                            'visible' => '$data->status_type != 1',
                            'options' => array('onclick' => 'editStatusType(this)', 'typeData' => 2),
                            'url' => '"javascript:void(0)"'
                        ),
                        'detail' => array(
                            'label' => '查看评论',
                            'options' => array('onClick' => 'detail(this)'),
                            'url' => '"javascript:void(0)"'
                        ),
                        'mobile' => array(
                            'label' => '查看手机号',
                            'options' => array('onClick' => 'showPhone(this)'),
                            'url' => '"javascript:void(0)"'
                        ),
                    ),
                ),
            ),
        )); ?>
    </div>
    <button type="button" onclick="GetCheckbox(0);" style="width:76px">批量屏蔽</button>
    <button type="button" onclick="GetCheckbox(1);" style="width:76px">批量通过</button>
</div>
<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">查看评论</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目类型</label>
                        <div class="col-sm-9">
                            <label class="col-sm-10" id="typeName" style="padding-top: 4px;"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目名称</label>
                        <div class="col-sm-9">
                            <label class="col-sm-9" id="projectName" style="padding-top: 4px;"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">评论内容</label>
                        <div class="col-sm-10">
                            <label class="col-sm-12" id="content" style="padding-top: 4px;"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">点赞数量</label>
                        <div class="col-sm-9">
                            <label class="col-sm-9" id="favorCount" style="padding-top: 4px;">0</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">分数</label>
                        <div class="col-sm-9">
                            <label class="col-sm-9" id="score" style="padding-top: 4px;" >0</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
<script>
    /**
     * 查看手机号
     */
    function showPhone(e) {
        var _openId = $(e).parent().prevAll("td[class*='openId']").html();
        $.ajax({
            type: 'post',
            url: '/show/showComment/showPhone',
            data: {'openId': _openId},
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    parent.layer.open({'content': res.data, 'title': '手机号'});
                } else {
                    layer.msg(res.msg);
                }
            }, error: function () {
                layer.msg('操作失败');
            }
        });
    }
    /**
     * 查看详情
     * @param e
     */
    function detail(e) {
        var _Id = $(e).parent().prevAll("td[class*='commentId']").html();
        $.ajax({
            type: 'post',
            url: '/show/showCommentReply/getDetail',
            data: {'id': _Id},
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    layer.closeAll();
                    $('#typeName').html(res.data.typeName);
                    $('#projectName').html(res.data.projectName);
                    $('#content').html(res.data.content);
                    $('#favorCount').html(res.data.favorCount);
                    $('#score').html(res.data.score);
                    $('#myModal').modal('show');

                } else {
                    layer.msg(res.msg);
                }
            }, error: function () {
                layer.msg('系统繁忙');
            }
        });
    }
    /**
     * 修改状态
     * @param e
     */
    function editStatusType(e) {
        var _type = $(e).attr('typeData');
        var _Id = $(e).parent().prevAll("td[class*='idData']").html();
        if (_type == 1) {
            layer.confirm('确定此评论只发布者可见吗？', {
                btn: ['确定', '取消'], //按钮
                shade: 0.8 //显示遮罩
            }, function () {
                _func(e, _type, _Id);
            }, function () {
            });
        } else {
            _func(e, _type, _Id);
        }
    }
    function _func(e, _type, _Id) {
        $.ajax({
            type: 'post',
            url: '/show/showCommentReply/editStatusType',
            data: {'type': _type, 'id': _Id},
            dataType: 'json',
            success: function (res) {
                var status_type = '';
                var typeData = 0;
                var button = '';
                if (_type == 1) {
                    status_type = '待审核';
                    typeData = 2;
                    button = '通过';
                } else {
                    status_type = '上线';
                    typeData = 1;
                    button = '屏蔽';
                }
                if (res.code == 0) {
                    $(e).parent().prevAll("td[class*='status_type']").html(status_type);
                    $(e).attr('typeData', typeData).attr('title', button).html(button);
                    layer.msg(res.msg);
                } else {
                    layer.msg(res.msg);
                }
            }, error: function () {
                layer.msg('系统繁忙');
            }
        });
    }
    /**
     * 批量操作
     */
    function GetCheckbox(type) {
        var data = new Array();
        $("input:checkbox[name='commentId[]']").each(function () {
            if ($(this).is(':checked')) {
                data.push($(this).val());
            }
        });
        if (data.length > 0) {
            $.post('/show/showCommentReply/statusAll', {'commentId[]': data, 'type': type}, function (data) {
                if (data == 'ok') {
                    if (type == 0) {
                        layer.msg('屏蔽成功！');
                        $("input:checkbox[name='commentId[]']").each(function () {
                            if ($(this).is(':checked')) {
                                $(this).parent().parent().find("td").last().prev().html("待审核");
                                $(this).parent().parent().find("td").last().find("a:eq(0)").html("通过").attr('title', "通过").attr('typeData', 2);
                            }
                        });
                    } else {
                        layer.msg('通过成功！');
                        $("input:checkbox[name='commentId[]']").each(function () {
                            if ($(this).is(':checked')) {
                                $(this).parent().parent().find("td").last().prev().html("上线");
                                $(this).parent().parent().find("td").last().find("a:eq(0)").html("屏蔽").attr('title', "屏蔽").attr('typeData', 1);
                            }
                        });
                    }
                }
            });
        } else {
            if (type == 0) {
                layer.msg("请选择要屏蔽的评论!");
            } else {
                layer.msg("请选择要通过的评论!");
            }
        }
    }
</script>
