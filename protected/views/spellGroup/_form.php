<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/8/11
 * Time: 15:11
 */
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
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'spell-group-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!--拼团电影id-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_id',array('id'=>'movieId','size'=>60,'class' => 'col-xs-6')); ?>
            </div>
        </div>
        <!--拼团电影名称-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name',array('id' => 'movie_names','size'=>60,'class' => 'col-xs-6','disabled'=>"disabled")); ?>
            </div>
        </div>
        <input type="hidden" id="movie_name" name="SpellGroup[movie_name]" value=<?php echo $model->movie_name;?> do="notnull">
        <!--标题-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>14,'class' => 'col-xs-6')); ?>
                <div style="color:#D42124;margin-bottom:10px;padding:0px">最多14个汉字</div>
            </div>
        </div>
        <!--活动开始时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--活动结束时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--成团数量-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'group_count', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'group_count',array('size'=>60,'maxlength'=>8,'class' => 'col-xs-3')); ?>
                <div style="color:#D42124;margin-bottom:10px;padding:0px">最多99,999,999个</div>
            </div>
        </div>
        <!--拼团人数-->
           <div class="form-group">
               <?php echo $form->labelEx($model, 'people_num', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
               <div class="col-sm-9">
                        <?php echo $form->textField($model,'people_num',array('size'=>60,'maxlength'=>8,'class' => 'col-xs-3')); ?>
               </div>
           </div>
         <!--拼团持续有效时长（小时）-->
       <div class="form-group">
           <?php echo $form->labelEx($model, 'keep_hour', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'keep_hour',array('size'=>60,'maxlength'=>8,'class' => 'col-xs-3')); ?>
                    <div style="color:#D42124;margin-bottom:10px;padding:0px">单位（小时）</div>
                </div>
       </div>
        <!--红包渠道-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'bonus_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'bonus_type',array('0' => '自有', '1' => '腾讯'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!--统计渠道-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'static_channel', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->textField($model,'static_channel',array('size'=>60,'maxlength'=>16,'class' => 'col-xs-3')); ?>
            </div>
        </div>
        <!--红包id-->
        <div class="form-group" <?php if($model->bonus_type==1){?>style="display: none;"<?php }?>>
            <?php echo $form->labelEx($model, 'bonus_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'bonus_id',array('size'=>60,'maxlength'=>16,'class' => 'col-xs-3')); ?>
            </div>
        </div>
        <!--红包金额-->
        <div class="form-group" <?php if($model->bonus_type==1){?>style="display: none;"<?php }?>>
            <?php echo $form->labelEx($model, 'bonus_value', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'bonus_value',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-3')); ?>
                <div style="color:#D42124;margin-bottom:10px;padding:0px">以分为单位，例如10元红包请填1000，并且要跟商业化的红包建的金额保持一致才能发红包</div>
            </div>
        </div>
        <!--状态-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('separator' => ' ')); ?>
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
<script>
    $(function () {
        //通过movieid获取影院名字
        $("#movieId").blur(function () {
            var movieId = $("#movieId").val();
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
                        $("#movie_name").val(msg);
                        $("#movie_names").val(msg);
                        $("#movie_name").text(msg);
                    } else {
                        $("#movie_name").val('');
                        $("#movie_names").val('');
                        $("#movieId").val('');
                        alert(msg);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        })
        $('input[name="SpellGroup[bonus_type]"]').click(function(){
            var val = $(this).val();
            if(val==0){
                $('input[name="SpellGroup[bonus_id]"]').parent().parent().show();
                $('input[name="SpellGroup[bonus_value]"]').parent().parent().show();
            }
            else{
                $('input[name="SpellGroup[bonus_id]"]').parent().parent().hide();
                $('input[name="SpellGroup[bonus_value]"]').parent().parent().hide();
            }
        });
    });
</script>
<?php $this->endWidget(); ?>
