<?php
$this->breadcrumbs = array(
    '红包管理' => array('index'),
);
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
<div class="page-header">
    <h1>红包管理</h1>
</div>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'hong-bao-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">红包开关
                    <span class="required">*</span></label>
                <div class="col-sm-9">
                    <input id="readBox_status_1" value="1" type="radio"
                           name="redEnvelop[redEnvelopStatus]"  <?php echo isset($data['redEnvelopStatus']) && $data['redEnvelopStatus'] == 1 ? 'checked' : '' ?> >
                    <label for="readBox_status_1">开</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input id="readBox_status_2" value="0" type="radio"
                           name="redEnvelop[redEnvelopStatus]"  <?php echo isset($data['redEnvelopStatus']) && $data['redEnvelopStatus'] == 0 ? 'checked' : '' ?> >
                    <label for="readBox_status_2">关</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="pid">活动ID
                </label>
                <div class="col-sm-9">
                    <input class="col-xs-3" name="redEnvelop[pid]" id="pid"
                           value="<?php echo isset($data['pid']) ? $data['pid'] : '' ?>"
                           type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="icon">icon图片地址
                </label>
                <div class="col-sm-9">
                    <input class="col-xs-6" name="redEnvelop[icon]" id="icon" type="text"
                           value="<?php echo isset($data['icon']) ? $data['icon'] : '' ?>"
                    >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="text1">描述1
                </label>
                <div class="col-sm-9">
                    <textarea class="col-xs-6" rows="4" name="redEnvelop[text1]"
                              id="text1"><?php echo isset($data['text1']) ? $data['text1'] : '' ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="text2">描述2
                </label>
                <div class="col-sm-9">
                    <textarea class="col-xs-6" rows="4" name="redEnvelop[text2]"
                              id="text2"><?php echo isset($data['text2']) ? $data['text2'] : '' ?></textarea>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        保存
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
</div>
<script type="text/javascript">
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    $("#hong-bao-form").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            'redEnvelop[pid]': {
            },
            'redEnvelop[readBoxStatus]': {
                required: true
            },
        },
        messages: {
            'redEnvelop[pid]': {
            },
            'redEnvelop[readBoxStatus]': {
                required: icon + "请选择开关"
            }
        },
        submitHandler: function (form) {
            layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
            <?php
            $url = Yii::app()->getController()->createUrl('/weixinsp/redEnvelop/save');
            $alert_url = Yii::app()->getController()->createUrl('/weixinsp/redEnvelop/index');
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
</script>