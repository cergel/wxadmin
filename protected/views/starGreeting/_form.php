<?php
/* @var $this StarGreetingController */
/* @var $model StarGreeting */
/* @var $form CActiveForm */
?>
<style>
    .error {
        color: red;
    }

    #phone_big {
        width: 254px;
        height: 520px;
        position: relative;
    }

    #phone_low {
        height: 422px;
        position: absolute;
        z-index: 1;
        left: 6px;
        bottom: 51px;
        width: 238px;
        background: rgb(255, 255, 255);
        border-radius: 2px;
        line-height: 54px;
        overflow: hidden;
    }

    .voiceDel {
        margin-left: 5px;
        margin-top: 3px;
    }
</style>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'starGreetings-greeting-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/moment.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model); ?>
            <?php if (!$model->getIsNewRecord()) { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->id; ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input <?php echo $model->type == 1 ? 'checked' : '' ?>
                        class="greetingType"
                        id="StarGreeting_type_1" value="1"
                        type="radio"
                        name="StarGreeting[type]">
                    <label for="StarGreeting_type_1">平日</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input <?php echo $model->type == 2 ? 'checked' : '' ?>
                        class="greetingType"
                        id="StarGreeting_type_2" value="2"
                        type="radio"
                        name="StarGreeting[type]">
                    <label for="StarGreeting_type_2">全天</label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'title', array('class' => 'col-xs-6')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'start_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9" data-autoclose="true">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?php echo $form->textField($model, 'start_time', array('class' => 'col-xs-4 datetimepicker', 'value' => $model->start_time ? date('Y-m-d', $model->start_time) : '')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'end_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9" data-autoclose="true">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?php echo $form->textField($model, 'end_time', array('class' => 'col-xs-4 datetimepicker', 'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="StarGreeting_type"
                       aria-required="true">
                    平台设置
                </label>
                <div class="col-sm-9">
                    <input <?php echo $model->channel_id != 3 ? 'checked' : '' ?>
                        id="StarGreeting_channelId_1" value="28"
                        type="radio"
                        name="StarGreeting[channel_id]">
                    <label for="StarGreeting_channelId_1">手Q端</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input <?php echo $model->channel_id == 3 ? 'checked' : '' ?>
                        id="StarGreeting_channelId_2" value="3"
                        type="radio"
                        name="StarGreeting[channel_id]">
                    <label for="StarGreeting_channelId_2">微信端</label>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-3 control-label no-padding-right" for="StarGreeting_title">分段时间设置</label>
            </div>
            <div id="greeting_show">
            </div>
            <hr>
            <div class="form-group" id="BGPreview">
                <?php echo $form->labelEx($model, 'bg_img', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <?php echo $form->fileField($model, 'bg_img', array('class' => 'col-xs-3')); ?>
                <input id="bg_url" value="<?php echo $model->bg_img ?>" type="hidden">
                <span class="help-inline col-xs-3">
					<span class="middle">图像格式PNG/JPG</span>
                </span>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'status', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input <?php echo $model->status == 1 ? 'checked' : '' ?>
                        id="StarGreeting_status_1" value="1"
                        type="radio"
                        name="StarGreeting[status]">
                    <label for="StarGreeting_status_1">发布</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input <?php echo $model->status == 0 ? 'checked' : '' ?>
                        id="StarGreeting_status_2" value="0"
                        type="radio"
                        name="StarGreeting[status]">
                    <label for="StarGreeting_status_2">不发布</label>
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
                    &nbsp; &nbsp; &nbsp;
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <script type="text/javascript">
        $(".greetingType").click(function () {
            var greetingType = $("input[name='StarGreeting[type]']:checked").val() - 0;
            $("#greeting_show").html($("#greeting_show_" + greetingType).html());
        });
    </script>
</div>
<!-- form -->
<div id="greeting_show_1" style="display: none">
    <?php
    $dateList = [['name' => '早晨', 'key' => 1, 'key_name' => 'morning'],
        ['name' => '上午', 'key' => 2, 'key_name' => 'forenoon'],
        ['name' => '下午', 'key' => 3, 'key_name' => 'afternoon'],
        ['name' => '晚上', 'key' => 4, 'key_name' => 'night']];
    foreach ($dateList as $val) {
        $key_name = $val['key_name']; ?>
        <hr>
        <div class="row">
            <div class="col-sm-1 col-sm-offset-1 text-right">
                <div class="form-group">
                    <label class="control-label no-padding-buttom"
                           for="StarGreeting_title"><?php echo $val['name'] ?></label>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label class="col-xs-2 control-label no-padding-buttom"
                           for="StarGreeting_title">明星图像</label>
                    <?php echo $form->fileField($model, 'portrait' . $val['key'], array('class' => 'col-xs-4 portrait')); ?>
                    <span class="help-inline col-xs-3">
                    <span class="middle">图像格式PNG</span>
                </span>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label no-padding-buttom"
                           for="StarGreeting_title">语音音频</label>
                    <?php echo $form->fileField($model, 'voice' . $val['key'], array('class' => 'col-xs-4 voice')); ?>
                    <span class="help-inline col-xs-3">
					<span class="middle">音频格式MPE/MP3</span>
                </span>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label no-padding-buttom"
                           for="StarGreeting_title">提示文案</label>
                    <input id="StarGreeting_start_time" class="col-xs-4"
                           name="StarGreeting[tips<?php echo $val['key'] ?>]"
                           value="<?php
                           echo isset($count2[$key_name]['tips']) && !empty($count2[$key_name]['tips']) ? $count2[$key_name]['tips'] : '' ?>"
                           type="text">
                    <input type="hidden" class="star_img"
                           value="<?php echo isset($count2[$key_name]['star_img']) && !empty($count2[$key_name]['star_img']) ? $count2[$key_name]['star_img'] : '' ?>">
                    <input type="hidden" class="voice_url"
                           value="<?php echo isset($count2[$key_name]['voice_url']) && !empty($count2[$key_name]['voice_url']) ? $count2[$key_name]['voice_url'] : '' ?>">
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label no-padding-buttom"
                           for="StarGreeting_title">跳转链接
                    </label>
                    <input id="StarGreeting_start_time" class="col-xs-4"
                           name="StarGreeting[jump_url<?php echo $val['key'] ?>]"
                           value="<?php echo isset($count2[$key_name]['jump_url']) && !empty($count2[$key_name]['jump_url']) ? $count2[$key_name]['jump_url'] : '' ?>"
                           type="text">
                </div>
                <div class="col-sm-6 col-sm-offset-6">
                    <button type="button" class="btn btn-xs btn-white preview" onclick="javascript:Preview(this);">
                        预览
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div id="greeting_show_2" style="display: none">
    <hr>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="form-group">
                <label class="col-xs-2 control-label no-padding-buttom"
                       for="StarGreeting_title">明星图像
                    <span class="required" aria-required="true">*</span>
                </label>
                <?php echo $form->fileField($model, 'portrait1', array('class' => 'col-xs-4 portrait')); ?>
                <span class="help-inline col-xs-3">
					<span class="middle">图像格式PNG</span>
                </span>
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label no-padding-buttom"
                       for="StarGreeting_title">语音音频
                </label>
                <?php echo $form->fileField($model, 'voice1', array('class' => 'col-xs-4 voice')); ?>
                <span class="help-inline col-xs-3">
					<span class="middle">音频格式MPE/MP3</span>
                </span>
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label no-padding-buttom"
                       for="StarGreeting_title">提示文案
                    <span class="required" aria-required="true">*</span>
                </label>
                <input id="StarGreeting_start_time" class="col-xs-4" name="StarGreeting[tips1]"
                       value="<?php echo isset($count1['tips']) && !empty($count1['tips']) ? $count1['tips'] : '' ?>"
                       type="text">
                <input type="hidden" class="star_img"
                       value="<?php echo isset($count1['star_img']) && !empty($count1['star_img']) ? $count1['star_img'] : '' ?>">
                <input type="hidden" class="voice_url"
                       value="<?php echo isset($count1['voice_url']) && !empty($count1['voice_url']) ? $count1['voice_url'] : '' ?>">
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label no-padding-buttom"
                       for="StarGreeting_title">跳转链接
                </label>
                <input id="StarGreeting_start_time" class="col-xs-4" name="StarGreeting[jump_url1]"
                       value="<?php echo isset($count1['jump_url']) && !empty($count1['jump_url']) ? $count1['jump_url'] : '' ?>"
                       type="text">
            </div>
        </div>
        <div class="col-sm-6 col-sm-offset-6">
            <button type="button" class="btn btn-xs btn-white preview" onclick="javascript:Preview(this);">
                预览
            </button>
        </div>
    </div>
</div>
<script type="text/javascript">
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    $("#starGreetings-greeting-form").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            'StarGreeting[status]': {
                required: true
            },
            'StarGreeting[type]': {
                required: true
            },
            'StarGreeting[title]': {
                required: true,
                maxlength: 60
            },
            'StarGreeting[start_time]': {
                required: true
            },
            'StarGreeting[end_time]': {
                required: true
            }
        },
        messages: {
            'StarGreeting[status]': {
                required: icon + "请选择是否发布"
            },
            'StarGreeting[type]': {
                required: icon + "请选择问候类型"
            }
            ,
            'StarGreeting[title]': {
                required: icon + "请输入问候名称",
                maxlength: icon + "问候名称最多60个字符"
            }
            ,
            'StarGreeting[start_time]': {
                required: icon + "请选择上线时间",
            }
            ,
            'StarGreeting[end_time]': {
                required: icon + "请选择下线时间",
            }
            ,
            'CinemaHallFeature[specific_description]': {
                maxlength: icon + "最长不能超过140字",
            }
        }
        ,
        submitHandler: function (form) {
            layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
            <?php
            if ($model->getIsNewRecord()) {
                $url = Yii::app()->getController()->createUrl('starGreeting/create');
                $alert_url = Yii::app()->getController()->createUrl('starGreeting/index');
            } else {
                $url = Yii::app()->getController()->createUrl('starGreeting/update/' . $model->id);
                $alert_url = Yii::app()->getController()->createUrl('starGreeting/Update/' . $model->id);
            }
            ?>
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
                    layer.msg('系统繁忙');
                }
            });
            return false;
        }
    })
    $(function () {
        $(".datetimepicker").datetimepicker({
            format: "YYYY-MM-DD"
        }).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });

    });
</script>
<!-- cache上传 -->
<script>
    /*
     * 检查文件格式
     */
    var PreviewStarImg = '';
    var PreviewVoiceUrl = '';
    function checkExt(obj) {
        var portrait = $(obj).find(".portrait");
        var voice = $(obj).find(".voice");
        var portraitVal = portrait.val();
        var voiceVal = voice.val();
        //图片格式限制
        //预览
        var portraitExt = portraitVal.substring(portraitVal.lastIndexOf("."));
        PreviewStarImg = $(obj).find(".star_img").val();
        if (portraitExt != ".png" && PreviewStarImg == '') {
            layer.msg("请选择PNG格式的图片!");
            return false;
        }
        //音频文件格式限制
        //预览
        PreviewVoiceUrl = $(obj).find(".voice_url").val();
        var voiceExt = voiceVal.substring(voiceVal.lastIndexOf("."));
        if (voiceExt != '.mp3' & voiceExt != '.mpe' && PreviewVoiceUrl == '') {
            layer.msg("请确保音频文件格式正确!");
            return false;
        }
        return true;
    }
    function ajaxUpload(obj) {
        var cur = $(obj);
        if (checkExt(cur)) {
            cur.wrap('<form enctype="multipart/form-data"/>');
            var options = {
                url: "<?php echo Yii::app()->getController()->createUrl('starGreeting/ajaxUpload') ?>",
                type: "post",
                dataType: "json",
                success: function (data) {
                    // 取消form包裹
                    cur.unwrap();
                    if (data.code == 0) {
                        var img_file_name = data.data.portrait_path != '' ? data.data.portrait_path : PreviewStarImg;
                        var voice_path = data.data.voice_path != '' ? data.data.voice_path : PreviewVoiceUrl;
                        var _tips = data.data.tips;
                        var bg_img = $("#bg_url").val();
                        layer.open({
                            type: 2,
                            title: '效果预览',
                            shadeClose: true,
                            shade: 0.8,
                            area: ['auto', '80%'],
                            content: '<?php echo Yii::app()->getController()->createUrl('starGreeting/preview');?>' + '?img_file_name=' + img_file_name + '&tips=' + _tips + '&voice_path=' + voice_path + '&bg_img=' + bg_img //iframe的url
                        });
                    } else {
                        layer.msg(data.msg);
                    }
                    //$('#myModal').modal({
                    //keyboard: true
                    //}).show();cur.after(  '<img class="img_file" src=' + img_file_name + ' height="200" width="300"/></td></tr>' );
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    cur.unwrap();
                    layer.msg(textStatus + "," + errorThrown);
                }
            };
            cur.parent("form").ajaxSubmit(options);    // 异步提交
        }
    }
    function doPreview(portrait_url, voice_url, tips1) {
        if (portrait_url == '' || voice_url == '' || tips1 == '') {
            layer.msg('预览参数错误');
        }
    }
    $(function () {
        var greetingType = $(".greetingType:checked").val();
        $("#greeting_show").html($("#greeting_show_" + greetingType).html());
    })
    function Preview(obj) {
        //文件缓存
        BackGroundPreview();
        var parentDom = $(obj).parent().parent();
        ajaxUpload(parentDom);
    }
    //背景缓存上传
    function BackGroundPreview() {
        var StarGreeting_bg_img = $("#StarGreeting_bg_img").val();
        var BGExt = StarGreeting_bg_img.substring(StarGreeting_bg_img.lastIndexOf("."));
        if (BGExt != '.png' & BGExt != '.jpg') {
            if (BGExt != '') {
                layer.msg("请确保背景图片格式正确!");
                return false;
            }
        } else {
            var Bcur = $("#BGPreview");
            Bcur.wrap('<form enctype="multipart/form-data"/>');
            var options = {
                url: "<?php echo Yii::app()->getController()->createUrl('starGreeting/AjaxBgUpload') ?>",
                type: "post",
                dataType: "json",
                success: function (data) {
                    Bcur.unwrap();
                    if (data.code == 0) {
                        var BGUrl = data.data.BGUrl;
                        $("#bg_url").val(BGUrl);
                    } else {
                        layer.msg(data.msg);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    Bcur.unwrap();
                    layer.msg(textStatus + "," + errorThrown);
                }
            };
            Bcur.parent("form").ajaxSubmit(options);    // 异步提交
        }
        return false;
    }
</script>
