<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js",
    CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'live-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
)); ?>

<div class="row">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'live-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model); ?>
        <?php
        if (Yii::app()->user->hasFlash('error')) {
            echo '<div class="alert alert-danger" role="alert">' . Yii::app()->user->getFlash('error') . '</div>';
        }
        ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'name',
                    array('size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '直播明星', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'star_name',
                    array('id' => 'star_name', 'size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '关联影片id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model, 'movie_id',
                    array('id' => 'movie_id', 'size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="form-group">
                <?php echo $form->labelEx($model, '微信分享主标题',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textField($model, 'wx_share_title',
                        array('id' => 'wx_share_title', 'size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'Qzone分享主标题',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textField($model, 'qzone_share_title',
                        array('id' => 'qzone_share_title', 'size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
                    不超过16个字
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, '分享描述',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textField($model, 'share_description',
                        array('id' => 'share_description', 'size' => 10, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, '分享缩略图',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="file" value="上传" name="LiveshowPic[sharepic_link]" id="sharepic">*尺寸100*100px,<30k
                    <label for=""><?php if (!empty($model->sharepic_link)) {
                             echo $model->sharepic_link;
                        } ?></label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '视频预览图',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="file" value="上传" name="LiveshowPic[pre_link]" id="sharepic">
                    <label for=""><?php if (!empty($model->beforelive_piclink)) {
                            echo $model->beforelive_piclink;
                        } ?></label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '投放平台',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <?php
                    if ($model->is_app) {
                        echo '<input type="checkbox" value="1" name="Liveshow[is_app]" id="is_app_id" checked>';
                    } else {
                        echo '<input type="checkbox" value="1" name="Liveshow[is_app]" id="is_app_id">';
                    }
                    ?>
                    <label for="is_app_id">APP</label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '预热banner图片',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="file" value="上传" name="LiveshowPic[pre_banner_pic]" id="sharepic">
                    <label for=""><?php if (!empty($model->pre_banner_pic)) {
                            echo $model->pre_banner_pic;
                        } ?></label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '预热页链接(APP)',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textField($model, 'pre_page_link',
                        array('id' => 'share_description', 'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '直播banner图片',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="file" value="上传" name="LiveshowPic[live_banner_pic]" id="sharepic">
                    <label for=""><?php if (!empty($model->live_banner_pic)) {
                            echo $model->live_banner_pic;
                        } ?></label>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '直播页链接(APP)',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textField($model, 'live_page_link',
                        array('id' => 'share_description' , 'class' => 'col-xs-10')); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, '直播开始时间',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="text" class="form-control date-timepicker" size="60" maxlength="200"
                           class="col-xs-5" id="img_start_time" name="Liveshow[start_time]" value="<?php if ($model->start_time) {
                               echo date("Y-m-d H:i:s", $model->start_time);} ?>">
                    <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, '直播结束时间',
                    array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div id="avatar_link" class="col-xs-9">
                    <input type="text" class="form-control date-timepicker" size="60" maxlength="200"
                           class="col-xs-5" id="img_start_time" name="Liveshow[end_time]" value="<?php if ($model->end_time) {
                        echo date("Y-m-d H:i:s", $model->end_time);} ?>">
                    <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
            </span>
                </div>
            </div>
            <div class="form-group">

            </div>
            <div class="row buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update'); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <script>
        $(function () {

            //通过movieid获取影院名字
            $("#movieIdButton").click(function () {
                var movieId = $("#movie_id").val();
                if (movieId == '')return false;

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
                            var html = '<div class="col-xs-2" style="border: solid 1px"> <input type="checkbox" name="Vote[movieIds][]" value="' + movieId + '"> <span>' + msg + '</span> <input type="hidden" name="Vote[movieNames][]" value="' + msg + '"></div>'
                            $("#movie_name").append(html);
                        } else {
                            alert(msg);
                        }
                    },
                    error: function ($data) {
                        alert("网络错误请重试")
                    }
                });
            });

            $("#giftIdButton").click(function () {
                var movieId = $("#gift_id").val();
                if (movieId == '')return false;

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
                            var html = '<div class="col-xs-2" style="border: solid 1px"> <input type="checkbox" name="Vote[movieIds][]" value="' + movieId + '"> <span>' + msg + '</span> <input type="hidden" name="Vote[movieNames][]" value="' + msg + '"></div>'
                            $("#movie_name").append(html);
                        } else {
                            alert(msg);
                        }
                    },
                    error: function ($data) {
                        alert("网络错误请重试")
                    }
                });
            });

            $("#add_btn").click(function () {
                var html = ' <tr><td><input type="text" class="form-control date-timepicker" size="60" maxlength="200" class="col-xs-10" name="Liveshow[img_start][]" id="img_start_time" name="Liveshow[filter_start][]" value=""><span class="input-group-addon"<i class="fa fa-clock-o bigger-110"></i> </span> </td> <td> <input type="text" class="form-control date-timepicker" size="60" maxlength="200" class="col-xs-10" name="Liveshow[img_start][]" id="img_start_time" name="Liveshow[filter_end][]" value=""><span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span></td><td> <input size="60" maxlength="200" class="col-xs-10" name="Liveshow[img_num][]" id="img_num" type="text" value=""></td></tr>'
                $("#tb_live_viewer_num").append(html);
                $('.date-timepicker').datetimepicker({
                    format: "YYYY-MM-DD HH:mm:ss"
                }).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            });
            $("#qzone_share_title").blur(function (e) {
                if ($("#qzone_share_title").val().length > 16) {
                    alert('qzone分享主题不允许超过16个字符');
                    return;
                }
            });
        })
    </script>

    <?php $this->endWidget(); ?>
