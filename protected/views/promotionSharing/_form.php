<style type="text/css">
    .error {
        color: #ce4844;
    }

    #voiceDel {
        margin-left: 5px;
        margin-top: 3px;
    }

    .none-padding {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: 20% !important;
    }

    .file {
        position: relative;
        display: inline-block;
        background: #6fb3e0;
        border: 1px solid #6fb3e0;
        padding: 4px 12px;
        overflow: hidden;
        color: #FFF;
        text-decoration: none;
        text-indent: 0;
        line-height: 20px;
    }

    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }

    .file:hover {
        background: #1c84c6;
        border-color: #78C3F3;
        color: #FFF;
        text-decoration: none;
    }
</style>
<div class="form">

    <?php
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    //时间控件
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/laydate/laydate.js");
    //弹窗控件
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/UploadImg.js");
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'promotion-sharing-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
    )); ?>

    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'start_time', array('for' => 'start_time', 'class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input id="start_time" size="60" maxlength="64" class="layer-date col-xs-5" do="notnull"
                           name="PromotionSharing[start_time]" type="text"
                           value="<?php echo isset($model->start_time) ? date('Y-m-d H:i:s', $model->start_time) : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'end_time', array('for' => 'end_time', 'class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input id="end_time" size="60" maxlength="64" class="layer-date col-xs-5" do="notnull"
                           name="PromotionSharing[end_time]" type="text"
                           value="<?php echo isset($model->end_time) ? date('Y-m-d H:i:s', $model->end_time) : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'activity_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'activity_name', array('size' => 60, 'maxlength' => 64, 'class' => 'col-xs-5', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'for_user', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div class="radio radio-info radio-inline">
                        <input type="radio" id="for_user1" value="1"
                            <?php echo isset($model->for_user) && $model->for_user == 1 ? 'checked' : ''; ?>
                               name="PromotionSharing[for_user]">
                        <label for="for_user1">新用户</label>
                    </div>
                    <div class="radio radio-info radio-inline">
                        <input type="radio" id="for_user2" value="2"
                            <?php echo isset($model->for_user) && $model->for_user == 2 ? 'checked' : ''; ?>
                               name="PromotionSharing[for_user]">
                        <label for="for_user2">老用户</label>
                    </div>
                    <code>注释:不选择视为活不区分新老</code>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'bonus_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'bonus_id', array('size' => 60, 'maxlength' => 64, 'class' => 'col-xs-5', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'bonus_url', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'bonus_url', array('class' => 'col-xs-5', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"
                       for="background_img">背景图片<span class="required">*</span></label>
                <div class="col-sm-9">
                    <div>
                        <img id="background_img"
                             src="<?php echo isset($model->background_url) ? $model->background_url : ''; ?>"/>
                        <input type="hidden" name="PromotionSharing[background_url]"
                               value="<?php echo isset($model->background_url) ? $model->background_url : ''; ?>"
                        />
                    </div>
                    <br>
                    <div class="file ">选择文件
                        <input type="file" id="background_file"/>
                    </div>
                    <code>注释:请上传小于512kb的图片文件</code>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"
                       for="background_img">平台<span class="required">*</span></label>
                <div class="col-sm-9">
                    <?php
                    foreach (PromotionSharing::getChannel() as $channel_id => $channel_value) {
                        ?>
                        <div class="checkbox checkbox-inline">
                            <input type="checkbox"
                                   name="channels[]"
                                   id="<?php echo $channel_id; ?>" <?php echo isset($channels) && in_array($channel_id, $channels) ? 'checked' : '' ?>
                                   value="<?php echo $channel_id; ?>">
                            <label for="<?php echo $channel_id; ?>"> <?php echo $channel_value; ?> </label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'state_type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div class="radio radio-info radio-inline">
                        <input type="radio" id="open_top1" value="1"
                            <?php echo isset($model->state_type) && $model->state_type == 1 ? 'checked' : ''; ?>
                               name="PromotionSharing[state_type]">
                        <label for="open_top1">发布</label>
                    </div>
                    <div class="radio radio-info radio-inline">
                        <input type="radio" id="open_top2" value="0"
                            <?php echo isset($model->state_type) && $model->state_type == 0 ? 'checked' : ''; ?>
                               name="PromotionSharing[state_type]">
                        <label for="open_top2">未发布</label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo isset($model->id) ? $model->id : '' ?>">
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
    <?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    (function () {
        new uploadPreview({
            UpBtn: "background_file",
            ImgShow: "background_img",
            ErrMsg: '选择文件错误,图片类型必须是png',
            Url: '/promotionSharing/ajaxUpload'
        });
    })();
    var start = {
        elem: '#start_time',
        format: 'YYYY-MM-DD hh:mm:ss',
        min: laydate.now(), //设定最小日期为当前日期
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: false,
        choose: function (datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };
    var end = {
        elem: '#end_time',
        format: 'YYYY-MM-DD hh:mm:ss',
        min: laydate.now(),
        max: '2099-06-16 23:59:59',
        istime: true,
        istoday: false,
        choose: function (datas) {
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    $("#promotion-sharing-form").validate({
        ignore: [],//取消忽略隐藏域
        errorPlacement: function (error, element) {
            if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
                error.appendTo(element.parent().parent());
            } else if (element.attr('type') == 'file') {
                error.appendTo(element.parent().parent());
            } else if (element.attr('type') == 'hidden') {
                error.appendTo(element.parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },
        rules: {
            'PromotionSharing[start_time]': {
                required: true,
            },
            'PromotionSharing[end_time]': {
                required: true,
            },
            'PromotionSharing[activity_name]': {
                required: true,
                maxlength: 255
            },
            'channels[]': {
                required: true,
            },
            'PromotionSharing[state_type]': {
                required: true,
            },
            'PromotionSharing[background_url]': {
                required: true,
            }
        },
        messages: {
            'PromotionSharing[start_time]': {
                required: icon + "请选上线时间!",
            },
            'PromotionSharing[end_time]': {
                required: icon + "请选择结束时间!",
            },
            'channels[]': {
                required: icon + "请选择平台!",
            },
            'PromotionSharing[state_type]': {
                required: icon + "请选择发布状态!",
            },
            'PromotionSharing[activity_name]': {
                required: icon + "请输入活动名称!",
            },
            'PromotionSharing[background_url]': {
                required: icon + "请选择背景图片!",
            }
        },
        submitHandler: function (form) {
            <?php
            $url = Yii::app()->getController()->createUrl('promotionSharing/create');
            if ($model->getIsNewRecord()) {
            $alert_url = Yii::app()->getController()->createUrl('promotionSharing/index');
            ?>
            <?php
            } else {
            $alert_url = Yii::app()->getController()->createUrl('promotionSharing/Update/' . $model->id);
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