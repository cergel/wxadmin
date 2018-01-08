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
        'id' => 'voice-comment-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
    ));
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
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
                <?php echo $form->labelEx($model, 'movie_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'movie_id', array('id' => 'movieId', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'actor_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'actor_id', array('id' => 'actor_id', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'voice', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->fileField($model, 'voice', array('class' => 'col-xs-4')); ?>
                    <span class="help-inline col-xs-3">
								<span class="middle">音频格式MPE/MP3</span>
                     </span>
                </div>
            </div>
            <?php
            if (!$model->getIsNewRecord()) {
                ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right">当前语音</label>
                    <div class="col-sm-9">
                        <input type="text" class="pull-left" readonly
                               value="<?php echo $model->fileName ? $model->fileName : '暂无'; ?>">
                        <?php if ($model->fileName) { ?>
                            <input type="hidden" value="0" name="VoiceComment[voiceDel]" id="voiceDelVal">
                            <button class="btn btn-outline btn-danger btn-xs" id="voiceDel"
                                    type="button"><i class="fa fa-times"></i>
                            </button>
                            <?php
                        } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'times', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input id="times" class="col-xs-2" type="text" value="<?php echo $model->times ?>"
                           name="VoiceComment[times]">&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="help-inline col-xs-2">S</label>
                    <span class="help-inline col-xs-3">
								<span class="middle">音频时长1s-60s</span>
                        </span>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'tips', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'tips', array('rows' => 5, 'cols' => 50)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'base_clicks', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input id="base_clicks" class="col-xs-2" type="text" maxlength="11"
                           value="<?php echo $model->base_clicks ?>" name="VoiceComment[base_clicks]">
                </div>
            </div>
             <div class="form-group">
                <?php echo $form->labelEx($model, 'base_favor', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input id="base_favor" class="col-xs-2" type="text" maxlength="11"
                           value="<?php echo $model->base_favor ?>" name="VoiceComment[base_favor]">
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'order', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'order', array('id' => 'order', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-2')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'status', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->radioButtonList($model, 'status', array(1 => '发布', 0 => '不发布'), array('id' => 'status',)); ?>
                </div>
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
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?> &nbsp; &nbsp; &nbsp;
    <script type="text/javascript">
        $(function () {
            $("#voiceDel").click(function () {
                layer.confirm('确定要删除语音么？', {
                    btn: ['确定', '取消'],
                    shade: false //不显示遮罩
                }, function () {
                    $("#voiceDelVal").val(1);
                    var ca = $('#voiceDel').parent().parent().hide();
                    layer.msg('删除成功,点击保存生效', {icon: 1});
                }, function () {
                    layer.msg('删除操作已取消', {shift: 6});
                });
            });
        });
        var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
        $("#voice-comment-form").validate({
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                'VoiceComment[movie_id]': {
                    number: true,
                    required: true,
                    min: 1,
                    maxlength: 12
                },
                'VoiceComment[actor_id]': {
                    number: true,
                    required: true,
                    min: 1,
                    maxlength: 12
                },
                'VoiceComment[tips]': {
                    required: true,
                    maxlength: 150
                }
            },
            messages: {
                'VoiceComment[movie_id]': {
                    number: icon + "影片ID只能是数字",
                    required: icon + "请输入影片ID",
                    min: icon + "请输入影片ID",
                    maxlength: icon + "影片ID最长12位"
                },
                'VoiceComment[actor_id]': {
                    number: icon + "影人ID只能是数字",
                    required: icon + "请输入影人ID",
                    min: icon + "请输入影人ID",
                    maxlength: icon + "影人ID最长12位"
                },
                'VoiceComment[tips]': {
                    required: icon + "请输入对应文字",
                    maxlength: icon + "文字长度最多150个字符"
                }
            },
            submitHandler: function (form) {
                <?php
                if ($model->getIsNewRecord()) {
                $url = Yii::app()->getController()->createUrl('voiceComment/create');
                $alert_url = Yii::app()->getController()->createUrl('voiceComment/index');
                ?>
                var _times = $('#times').val() - 0;
                var _VoiceComment_voice = $('#VoiceComment_voice').val();
                if (_times == 0 && _VoiceComment_voice != '') {
                    layer.msg('请输入音频时间');
                    return false;
                }
                if (_times > 0 && _VoiceComment_voice == '') {
                    layer.msg('请选择音频文件');
                    return false;
                }
                <?php
                } else {
                $url = Yii::app()->getController()->createUrl('voiceComment/update/' . $model->id);
                $alert_url = Yii::app()->getController()->createUrl('voiceComment/Update/' . $model->id);
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
    </script>