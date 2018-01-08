<style>
    .error {
        color: red;
    }
</style>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'show-comment-form',
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
            <?php echo $form->labelEx($model, 'type_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <input type="text" value="<?php echo $model->type_name ?>" class="col-xs-10" readonly>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <input type="text" value="<?php echo $model->project_name ?>" class="col-xs-10" readonly>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'content', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'favor_count', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <input type="text" value="<?php echo $model->favor_count ?>" class="col-xs-10" readonly>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'base_favor_count', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'base_favor_count', array('size' => 60, 'maxlength' => 100, 'class' => 'col-xs-10')); ?>
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
<?php $this->endWidget(); ?>
<script>
    $(function () {
        var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
        $("#show-comment-form").validate({
            rules: {
                'ShowComment[base_favor_count]': {
                    required: true,
                    number: true,
                },
                'ShowComment[content]': {
                    required: true,
                    maxlength: 140
                },
            },
            messages: {
                'ShowComment[base_favor_count]': {
                    required: icon + "注水赞不能为空",
                    number: icon + "注水赞只能为数字",
                },
                'ShowComment[content]': {
                    required: icon + "评论不能为空",
                    number: icon + "评论长度不能超过256个字符",
                },
            }, submitHandler: function (form) {
                <?php
                $url = Yii::app()->getController()->createUrl('showComment/Update?id=' . $model->id);
                ?>
                $(form).ajaxSubmit({
                    type: 'post',
                    url: '<?php echo $url?>',
                    dataType: 'json',
                    success: function (res) {
                        layer.msg(res.msg);
                        if (res.code == 0) {
                            location.href = "<?php echo $url?>";
                        }
                    }, error: function () {
                        layer.msg('操作失败(确保非重复插入)');
                    }
                });
                return false;
            }
        })
    })
</script>