<?php

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_id', array('id' => 'movieId', 'rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_name', array('id' => 'movieName', 'rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">图片 <span class="required">*</span></label>            <div class="col-sm-9">
                <img width="200" height="150" src="<?php echo $model->pic;?>">
                <input  name="UpLoadFile"    type="file" >            </div>

        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'remark', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'remark', array('rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'start_time', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'end_time', array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'link', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'link', array('rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'order', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'order', array('rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
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
        //根据影片ID获取影片名称
        $("#movieId").blur(function () {
            var movieId = $("#movieId").val();
            if (movieId == '') {
                alert("无此ID对应的影片");
                return false;
            }

            $.ajax({
                data: "movieId=" + movieId,
                url: "/pee/getMovieNameByMovieId",
                type: "POST",
                dataType: 'json',
                async: false,
                success: function (data) {
                    var succ = data.succ;
                    var msg = data.msg;
                    if (succ == 1) {
                        $("#movieName").val(msg);
                    } else {
                        alert(msg);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });
    })

</script>