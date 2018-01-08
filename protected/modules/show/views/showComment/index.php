<?php
$this->breadcrumbs = array(
    '评论' => array('index'),
    '管理',
);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
?>
<div class="page-header">
    <h1>管理评论</h1>
    <a class="btn btn-success" onclick="upload()">导出</a>
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
                    'name' => 'content',
                    'value' => 'SensitiveWords::model()->isSensitiveWordInfo($data->content)',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'score',
                    'filter' => CHtml::activeDropDownList($model, 'score', (['' => '全部', '20' => '20', '40' => '40', '60' => '60', '80' => '80', '100' => '100'])),
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'favor_count',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'reply_count',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'project_name',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'type_name',
                    'filter' => CHtml::activeDropDownList($model, 'type_name', (['' => '全部',
                        '旅游演艺' => '旅游演艺',
                        '周边商品' => '周边商品',
                        'LIVE秀' => 'LIVE秀',
                        '休闲娱乐' => '休闲娱乐',
                        '戏曲综艺' => '戏曲综艺',
                        '舞蹈芭蕾' => '舞蹈芭蕾',
                        '展览活动' => '展览活动',
                        '音乐会' => '音乐会',
                        '儿童亲子' => '儿童亲子',
                        '话剧音乐剧' => '话剧音乐剧',
                        '体育赛事' => '体育赛事',
                        '演唱会' => '演唱会',
                    ])),
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'channelId',
                    'value' => 'ShowComment::model()->getChannelName($data->channelId)',
                    'filter' => CHtml::activeDropDownList($model, 'channelId', (['' => '全部', '3' => '微信', '8' => 'IOS', '9' => '安卓', '28' => '手q'])),
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'created',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 'updated',
                    'filter' => '',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 100
                    ),
                ),
                array(
                    'name' => 'openId',
                    'htmlOptions' => array(
                        'width' => 70,
                        'class' => 'openId',
                    )
                ),
                array(
                    'name' => 'checkstatus',
                    'filter' => CHtml::activeDropDownList($model, 'checkstatus', (['' => '全部', '0' => '不含敏感词', '1' => '含有敏感词'])),
                    'value' => '$data->checkstatus==1?"含有敏感词":"不含敏感词"',
                    'htmlOptions' => array(
                        'width' => 50
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
                    'template' => '{edit} {shield} {pass} {mobile} ',
                    'buttons' => array(
                        'edit' => array(
                            'label' => '编辑',
                            'url' => '"/show/showComment/update?id=".$data->id',
                            'options' => array()
                        ),
                        'shield' => array(
                            'label' => '屏蔽',
                            'options' => array('onclick' => 'editStatusType(this)', 'typeData' => 1),
                            'visible' => '$data->status_type != 0',
                            'url' => '"javascript:void(0)"'
                        ),
                        'pass' => array(
                            'label' => '通过',
                            'visible' => '$data->status_type != 1',
                            'options' => array('onclick' => 'editStatusType(this)', 'typeData' => 2),
                            'url' => '"javascript:void(0)"'
                        ),
                        'mobile' => array(
                            'label' => '查看手机号',
                            'options' => array('onClick' => 'showPhone(this)', 'openId' => $model->openId),
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
<script>
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
            url: '/show/showComment/editStatusType',
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
     * 查看手机号
     * @param e
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
     * 下载CSV
     */
    function upload() {
        var _obj = new Object();
        var _inputs = $("input[name*='ShowComment']").each(function () {
            var _name = $(this).attr('name');
            if ($(this).val().length > 0) {
                _obj[_name] = $(this).val();
            }
        });
        var _selects = $("select[name*='ShowComment']").each(function () {
            var _name = $(this).attr('name');
            if ($(this).val().length > 0) {
                _obj[_name] = $(this).val();
            }
        });
        var params = decodeURIComponent($.param(_obj, true));
        document.location.href = ('/show/showComment/upload?' + params);
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
            $.post('/show/showComment/statusAll', {'commentId[]': data, 'type': type}, function (data) {
                if (data == 'ok') {
                    if (type == 0) {
                        layer.msg('屏蔽成功！');
                        $("input:checkbox[name='commentId[]']").each(function () {
                            if ($(this).is(':checked')) {
                                $(this).parent().parent().find("td").last().prev().html("待审核");
                                $(this).parent().parent().find("td").last().find("a:eq(1)").html("通过").attr('title', "通过").attr('typeData', 2);
                            }
                        });
                    } else {
                        layer.msg('通过成功！');
                        $("input:checkbox[name='commentId[]']").each(function () {
                            if ($(this).is(':checked')) {
                                $(this).parent().parent().find("td").last().prev().html("上线");
                                $(this).parent().parent().find("td").last().find("a:eq(1)").html("屏蔽").attr('title', "屏蔽").attr('typeData', 1);
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
