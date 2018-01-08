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
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'ad-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>

        <div class="form-group">
            <?php echo CHtml::label('平台', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'channel',$model->getChannelList(),array('separator'=>'  ','do'=>"checkbox"));?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_id',array('id'=>'movieId','size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name',array('id' => 'movie_names','size'=>60,'maxlength'=>200,'class' => 'col-xs-10','disabled'=>"disabled")); ?>
            </div>
        </div>
        <input type="hidden" id="movie_name" name="MovieNews[movie_name]" value=<?php echo $model->movie_name;?> do="notnull">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'new_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'new_type',MovieNews::getNewTypeList(), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'tag', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'tag',array('size'=>60,'maxlength'=>4,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <!--  跳转链接地址  -->
        <?php foreach($model->getChannelList() as $key=>$list){  ?>
            <div class="form-group">
                <?php echo CHtml::label($list.'跳转页面', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php if(!empty($channelUrl[$key])){ ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value="<?php echo $channelUrl[$key];  ?>"  />
                    <?php }else{   ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'跳转页面' ?>" type="text" placeholder="" value=""  />
                    <?php  } ?>
                </div>
            </div>

        <?php } ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('class' => 'form-control date-timepicker','do'=>"notnull")); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker','do'=>"notnull")); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'order', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'order',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'share_show_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_show_title',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'share_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_title',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'share_second_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_second_title',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'share_url', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'share_url',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('分享图标', 'share_img', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="<?php echo  $model->share_img;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'share_img',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 正方形，小于32Kb。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo CHtml::label('定向城市', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CitySelectorWidget',array(
                        'name' => 'cities',
                        'selectedCities' => $selectedCities
                    )); ?>
                </div>
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
    </div>
</div>


<script>
    $(function () {

        //通过movieid获取影院名字
        $("#movieId").blur(function () {
            var movieId = $("#movieId").val();
            if(movieId == '')return false;
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
        });

        $("#submit").click(function(){
            //该死的浏览器兼容性
            var returnInfo = true;
            //影片id
            if(!$("#movieId").val()){
                alert('请输入影片id');
                $("#movieId").focus();
                return false;
            }
            if(!$("#movie_name").val()){
                alert('影片名称异常，请重新输入影片');
                $("#movieId").focus();
                return false;
            }
            var checboxValueInfo ='';
            $("input:checkbox[name='MovieNews[channel][]']").each(function (){
                if($(this).is(':checked')){
                    checboxValueInfo = $(this).val();
                    var channelKey = 'channelUrl'+$(this).val();
                    if($("#"+channelKey).val() == ''){
                        alert('请填写'+$("#"+channelKey).attr('channelName'));
                        $("#"+channelKey).focus();
                        returnInfo = false;
                        return false;
                    }
                }
            });
            if(checboxValueInfo == ''){
                alert('平台为必选');
                return false;
            }
            if(returnInfo == false){
                return false;
            }
            $("[do=notnull]").each(function(){

                if($(this).val() == ''){
                    alert('请检查必填数据');
                    $(this).focus();
                    returnInfo = false;
                    return returnInfo;
                }
            });
            return returnInfo;
        })

    });
</script>



<?php $this->endWidget(); ?>
