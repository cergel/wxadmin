<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal', 'autocomplete' => 'off', 'name' => "BasicInfo")
));
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->clientScript->registerScript('form', "

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
?>


<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_id', array('id' => 'movie_id', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_name', array('id' => 'movie_names', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6','disabled'=>"disabled")); ?>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'movie_name',array('id' => 'movie_name'))?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model, 'start_time', array('id' => 'start_time', 'size' => 60, 'maxlength' => 20, 'class' => 'form-control  date-timepicker')); ?>
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
                    <?php echo $form->textField($model, 'end_time', array('id' => 'end_time', 'size' => 60, 'maxlength' => 20, 'class' => 'form-control  date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'pos', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'pos', array('id' => 'pos', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div id="statucRadio" class="col-sm-9">
                <div class="col-xs-1"><input type="radio" name="MovieOrder[status]"
                                             value="1"
                        <?php
                        if(isset($model->status)){
                            $r=(isset($model->status) && ($model->status == 1)) ? 'checked' : '';
                        }else{
                            $r = 'checked';
                        }
                        echo $r;
                        ?>
                        >开启
                </div>
                <div class="col-xs-1"><input type="radio" name="MovieOrder[status]"
                                             value="0" <?php echo (isset($model->status) && ($model->status == 0)) ? 'checked' : '' ?>>关闭
                </div>
                <?php //echo $form->textField($model, 'status', array('id' => 'status', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'content', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'content', array('id' => 'content', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
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
        <input id="modelId"  type="hidden" value="<?php echo isset($model->id)?$model->id:0;?>">
    </div>
</div>
<script>
    $(function () {

        //通过movieid获取影院名字
        $("#movie_id").blur(function () {
            var movieId = $("#movie_id").val();
            $.ajax({
                data: "movieId=" + movieId,
                url: "/movieOrder/getMovieNameByMovieId",
                type: "POST",
                dataType: 'json',
                async: false,
                success: function (data) {
                    var succ = data.succ;
                    var msg = data.msg;
                    if (succ == 1) {
                        $("#movie_name").val(msg);
                        $("#movie_names").val(msg);
                        $("#movie_name").text(msg);
                    } else {
                        $("#movie_name").val('')
                        alert(msg);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });


        $("#submit").click(function(){
            var movieId = $("#movie_id").val();
            var startTime = $("#start_time").val();
            var endTime = $("#end_time").val();
            var status = $('#statucRadio input[name="MovieOrder[status]"]:checked').val();
            var pos = $("#pos").val();
            var modelId = $("#modelId").val();

            if(movieId=='' || startTime=='' || endTime=='' || status=='' || pos=='' || modelId==''){
                alert('参数不完整');
                return false;
            }

            var flag=false;
            $.ajax({
                data: "movieId=" + movieId + "&startTime="+startTime+"&endTime="+endTime+"&status="+status+"&pos="+pos+"&modelId="+modelId,
                url: "/movieOrder/checkTime",
                type: "POST",
                dataType: 'json',
                async: false,
                success: function (data) {
                    if(data.succ==1){
                        flag = true;
                    }else{
                        alert(data.msg);
                        return false;
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
            return flag;
        })
    });
</script>


<?php
$this->endWidget();
?>


