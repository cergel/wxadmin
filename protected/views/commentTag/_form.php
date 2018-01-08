<?php
/* @var $this CommentTagController */
/* @var $model CommentTag */
/* @var $form CActiveForm */
?>
<style>
    .error {
        color: red;
    }
</style>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'comment-tag-form',
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
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'tag_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'tag_name', array('class' => 'col-xs-4',)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'tag_content', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'tag_content', array('class' => 'col-xs-4',)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'comment_type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <input <?php echo $model->comment_type == 1 ? 'checked' : '' ?>
                        class="greetingType"
                        id="StarGreeting_type_1" value="1"
                        type="radio"
                        name="CommentTag[comment_type]">
                    <label for="StarGreeting_type_1">好评</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input <?php echo $model->comment_type == 2 ? 'checked' : '' ?>
                        class="greetingType"
                        id="StarGreeting_type_2" value="2"
                        type="radio"
                        name="CommentTag[comment_type]">
                    <label for="StarGreeting_type_2">差评</label>
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


</div><!-- form -->
<script type="text/javascript">
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    $("#comment-tag-form").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            'CommentTag[tag_name]': {
                required: true,
                maxlength: 100
            },
            'CommentTag[tag_content]': {
                required: true
            },
            'CommentTag[comment_type]': {
                required: true
            }
        },
        messages: {
            'CommentTag[tag_name]': {
                required: icon + "标签不能为空!",
                maxlength: icon + "标签不能超过100个字符!"
            },
            'CommentTag[tag_content]': {
                required: icon + "映射关键字不能为空!",
            },
            'CommentTag[comment_type]': {
                required: icon + "请选择标签属性!",
            }
        },
        submitHandler: function (form) {
            layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
            <?php
            if ($model->getIsNewRecord()) {
                $url = Yii::app()->getController()->createUrl('commentTag/create');
                $alert_url = Yii::app()->getController()->createUrl('commentTag/index');
            } else {
                $url = Yii::app()->getController()->createUrl('commentTag/update/' . $model->id);
                $alert_url = Yii::app()->getController()->createUrl('commentTag/Update/' . $model->id);
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
</script>