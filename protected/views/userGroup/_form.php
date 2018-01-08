<?php
/* @var $this UserGroupController */
/* @var $model UserGroup */
/* @var $form CActiveForm */
?>
<style type="text/css">
    .error {
        color: red;
    }

    #voiceDel {
        margin-left: 5px;
        margin-top: 3px;
    }
</style>
<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-group-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jstree.min.js");
    ?>

    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <?php if (!$model->getIsNewRecord()) { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->id; ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'groupName', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'groupName', array('class' => 'col-xs-4',
                        'maxlength' => "12",
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'blackOrWhite', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input <?php echo $model->blackOrWhite == 1 ? 'checked' : '' ?>
                        class="greetingType"
                        id="UserGroup_type_1" value="1"
                        type="radio"
                        name="UserGroup[blackOrWhite]">
                    <label for="UserGroup_type_1">黑名单</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input <?php echo $model->blackOrWhite == 2 ? 'checked' : '' ?>
                        class="greetingType"
                        id="UserGroup_type_2" value="2"
                        type="radio"
                        name="UserGroup[blackOrWhite]">
                    <label for="UserGroup_type_2">白名单</label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'authList', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <a type="button" class="btn btn-w-m btn-white" onclick='EditList()'>选择模块</a>
                </div>
                <input type="hidden" id="authList" name="UserGroup[authList]"
                       value='<?php echo $model->authList ?>'>
            </div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
                    </button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        重置
                    </button>
                    &nbsp; &nbsp; &nbsp;
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<!-- 板块地图-->
<!--
<div>
-->

<div class="modal inmodal fade" id="ModalList" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title">选择模块</h4>
            </div>
            <form id="ModuleForm">
                <div class="modal-body" id="ModularList">
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" onclick="Choice()" class="btn btn-primary" data-dismiss="modal">选择</button>
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //全选反选
    function MainChange(e) {
        var _id = 'modular' + $(e).attr('modular');
        if ($(e).is(':checked')) {
            $('#' + _id).find(':checkbox').prop('checked', true);
        } else {
            $('#' + _id).find(':checkbox').prop('checked', false);
        }
    }
    function ModularChange(e) {
        var _id = $(e).parent().parent().attr('id');
        _id = _id.replace('modular', '');
        var _checkbox = $(e).parent().parent().find(':checkbox');
        var chknum = _checkbox.size();
        var chk = 0;
        _checkbox.each(function () {
            if ($(this).prop("checked") == true) {
                chk++;
            }
        });
        if (chknum == chk) {
            $('#' + _id).prop("checked", true);
        } else {
            $('#' + _id).prop("checked", false);
        }
    }
    //展示授权列表
    function EditList() {
        var _authList = $('#authList').val();
        _authList = _authList == '' ? '' : jQuery.parseJSON(_authList);
        var _data = {'authList': _authList};
        $('#ModularList').load('/userGroup/modulesList', _data, function () {
            $('#ModalList').modal('show');
        })
    }
    //选择授权列表
    function Choice() {
        var _data = $('#ModuleForm').serializeArray();
        var authList = new Array();
        if (_data.length <= 0) {
            layer.msg('请选择授权列表');
            return false;
        }
        $.each(_data, function () {
            authList.push(this.value);
        });
        $("#authList").val(JSON.stringify(authList));
    }
    //表单提交
    $(function () {
        var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
        $("#user-group-form").validate({
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                'UserGroup[groupName]': {
                    required: true,
                    maxlength: 100
                },
                'UserGroup[type]': {
                    required: true,
                },
                'UserGroup[authList]': {
                    required: true,
                },
            },
            messages: {
                'UserGroup[groupName]': {
                    required: icon + "请输入分组名称",
                    maxlength: icon + "分组名称最长12位"
                },
                'UserGroup[type]': {
                    required: icon + "请选择黑名单或白名单",
                },
                'UserGroup[authList]': {
                    required: icon + "请选择权限列表",
                },
            },
            submitHandler: function (form) {
                <?php
                if ($model->getIsNewRecord()) {
                    $url = Yii::app()->getController()->createUrl('userGroup/create');
                    $alert_url = Yii::app()->getController()->createUrl('userGroup/index');
                } else {
                    $url = Yii::app()->getController()->createUrl('userGroup/update/' . $model->id);
                    $alert_url = Yii::app()->getController()->createUrl('userGroup/Update/' . $model->id);
                }
                ?>
                layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
                $(form).ajaxSubmit({
                    type: 'post',
                    url: '<?php echo $url?>',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            location.href = "<?php echo $alert_url?>";
                        } else {
                            layer.msg(res.msg);
                        }
                    }, error: function () {
                        layer.msg('操作失败');
                    }
                });
                return false;
            }
        })
    })
</script>