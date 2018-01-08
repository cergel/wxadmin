<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");

Yii::app()->clientScript->registerScript('form', "
    function update_form() {
 		var thisid = $('#Pee_is_pee :radio:checked').val();
		if(thisid ==1){
			$('[do=peeinfo]').show();
		}else{
		    $('[do=peeinfo]').hide();
		}

    }


    $('#Pee_is_pee :radio').click(function () {
        update_form();
    });

    update_form();

");

$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal', 'autocomplete' => 'off', 'name' => "BasicInfo")
));
?>

<style media="screen">
    .niaodian{
        background: #f7f7f7;
        height: 80%;
        overflow: auto;
        overflow-x:hidden;
    }
    label,.goon{
        text-align: right;
    }
    label i{
        color:red;

    }
    .form-group{
        display: block;
        clear: both;
        margin: 10px;
        height: 80%;
        overflow: auto;
    }
    .tip{
        line-height: 34px;
        color:#ff9232;
    }
    .line{
        width:100%;
        height: 2px;
        background:#e2e2e2;
        display: block;
        margin-bottom: 20px;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if(empty($model->id)){ ?>
                    <?php echo $form->textField($model, 'id', array('id' => 'id', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
                <?php }else{   ?>
                    <?php echo $form->textField($model, 'id', array('id' => 'id', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6','disabled'=>"disabled")); ?>
                    <input type="hidden" id="id" name="Pee[id]" value=<?php echo $model->id;?>>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_name', array('id' => 'movie_names', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6','disabled'=>"disabled")); ?>
            </div>
        </div>
        <input type="hidden" id="movie_name" name="Pee[movie_name]" value=<?php echo $model->movie_name;?>>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'mobile_no', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'mobile_no',array('id'=>'mobile_no','size'=>40,'maxlength'=>20,'class' => 'col-xs-6','do'=>'notnull')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'open_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'open_id',array('id'=>'open_id','size'=>12,'maxlength'=>12,'class' => 'col-xs-6','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'nick_name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'nick_name',array('id'=>'nick_name','size'=>12,'maxlength'=>12,'class' => 'col-xs-6','disabled'=>"disabled")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'head_img', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9" id="head_img">
                <?php if(!$model->isNewRecord){ ?>
                <img src="<?php echo $model->head_img  ?>">
                <?php } ?>
            </div>

        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'is_pee', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'is_pee',array('1' => '有尿点', '0' => '无尿点'), array('separator' => ' ')); ?>
            </div>
        </div>

        <!-- 尿点内容开始-->

        <div class="form-group" do="peeinfo">

            <!-- 内容 -->
            <?php if($model->isNewRecord || empty($peeInfo) ||  empty($model->is_pee) ){ ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'recommend_pee', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9" do="recommend_pee_info">
                        <?php
                        $model->pee_num = $model->pee_num > 1 ?$model->pee_num:1;
                        $str = '';
                            for($i=1;$i <= $model->pee_num;$i++){
                                $str .='<input id="Pee_recommend_pee_"'.$i.' value="'.$i.'"';
                                if($i ==  $model->recommend_pee){
                                    $str.= ' checked="true" ';
                                }
                                $str.= ' type="radio" name="Pee[recommend_pee]">&nbsp;&nbsp;';
                                $str .= '<label for="Pee_recommend_pee_'.$i.'">'.$i.'</label>';
                            }
                        echo $str;
                        ?>
                        <?php // echo $form->radioButtonList($model,'recommend_pee',array('1' => '1'), array('separator' => ' ')); ?>
                    </div>
                </div>
                <div>
                    <label class="col-sm-3 control-label no-padding-right required" for="Pee_id">内容
                        <span class="required">&nbsp;</span>
                    </label>
                    <div class="niaodian" do="peeList" >
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">尿点一时段 <i>*</i></label>
                            <div class="col-sm-2">
                                <input class="form-control"  name="start_time[]" type="text" placeholder="" value="" do="notnull" doint ="1" />
                            </div>
                            <div class="col-sm-4">
                                <span class="tip"><font style="color:#000;">分钟</font>   (精确到分钟,如：30~35分钟)</span>
                            </div>
                        </div>
                        <input  name="pid[]" type="hidden" value=""/>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">尿点持续时长 <i>*</i></label>
                            <div class="col-sm-2">
                                <input class="form-control"  type="text" name="end_time[]" placeholder="" value="" do="notnull" doint ="1" />
                            </div>
                            <div class="col-sm-4">
                                <span class="tip"><font style="color:#000;">分钟</font></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">尿点开始剧情 <i>*</i></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="3" name="pee_start_info[]"  do="notnull" domax="160" ></textarea>
                            </div>
                            <div class="col-sm-3">
                                <span class="tip"> 最多160字符</span>
                            </div>
                        </div><div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">尿点理由 <i>*</i></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="3" name="pee_reason[]"  do="notnull" domax="86" domin="20" ></textarea>
                            </div>
                            <div class="col-sm-3">
                                <span class="tip"> 最少20字符，最多86字符</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">错过的剧情 <i>*</i></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="3" name="pee_error_info[]" do="notnull" ></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px;">
                            <label for="disabledSelect"  class="col-sm-2 control-label">尿尿人数 <i>*</i></label>
                            <div class="col-sm-2">
                                <input class="form-control"  type="text" name="base_pee_count[]" placeholder="" value="0" do="notnull" doint ="1"/>
                            </div>
                            <div class="col-sm-3">
                                <span class="tip">实际人数：0人</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label"> <i></i></label>
                            <div class="col-sm-6" style="text-align:right;margin-bottom:45px;font-size:12px;">
                                <a href="javascript:void(0)" style="font-size:12px;" do="del_pee" v="">删除此尿点</a>
                            </div>
                        </div>
                    </div>

                </div>


            <?php }else { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'recommend_pee', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9" do="recommend_pee_info">
                        <?php
                        $model->pee_num = $model->pee_num > 1 ?$model->pee_num:1;
                        $str = '';
                        for($i=1;$i <= $model->pee_num;$i++){
                            $str .='<input id="Pee_recommend_pee_"'.$i.' value="'.$i.'"';
                            if($i ==  $model->recommend_pee){
                                $str.= ' checked="true" ';
                            }
                            $str.= ' type="radio" name="Pee[recommend_pee]">&nbsp;&nbsp;';
                            $str .= '<label for="Pee_recommend_pee_'.$i.'">'.$i.'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        echo $str;
                        ?>
                        <?php // echo $form->radioButtonList($model,'recommend_pee',array('1' => '1','2'=>'2'), array('separator' => ' ')); ?>
                    </div>
                </div>
            <?php  foreach($peeInfo as $key=>$val){  ?>
                    <div>
                        <label class="col-sm-3 control-label no-padding-right required" for="Pee_id">内容
                            <span class="required">&nbsp;</span>
                        </label>
                            <div class="niaodian" do="peeList" >
                                <i class="line"></i>
                                <div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">尿点一时段 <i>*</i></label>
                                    <div class="col-sm-2">
                                        <input class="form-control"  name="start_time[]" type="text" placeholder="" value="<?php echo $val->start_time; ?>" do="notnull" doint ="1" />
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="tip"><font style="color:#000;">分钟</font>   (精确到分钟,如：30~35分钟)</span>
                                    </div>
                                </div>
                                <input  name="pid[]" type="hidden" value="<?php echo $val->p_id; ?>"/>
                                <div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">尿点持续时长 <i>*</i></label>
                                    <div class="col-sm-2">
                                        <input class="form-control"  type="text" name="end_time[]" placeholder="" value="<?php echo $val->end_time; ?>" do="notnull" doint ="1"  />
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="tip"><font style="color:#000;">分钟</font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">尿点开始剧情 <i>*</i></label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" rows="3" name="pee_start_info[]" do="notnull"  domax="160" ><?php echo $val->pee_start_info; ?></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <span class="tip"> 最多160字符</span>
                                    </div>
                                </div><div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">尿点理由 <i>*</i></label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" rows="3" name="pee_reason[]" do="notnull" domax="86" domin="20" ><?php echo $val->pee_reason; ?></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <span class="tip"> 最少20字符，最多86字符</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">错过的剧情 <i>*</i></label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" rows="3" name="pee_error_info[]" do="notnull" ><?php echo $val->pee_error_info; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:20px;">
                                    <label for="disabledSelect"  class="col-sm-2 control-label">尿尿人数 <i>*</i></label>
                                    <div class="col-sm-2">
                                        <input class="form-control"  type="text" name="base_pee_count[]" placeholder="" value="<?php echo $val->base_pee_count; ?>" do="notnull"  doint ="1"/>
                                    </div>
                                    <div class="col-sm-3">
                                        <span class="tip">实际人数：<?php echo $val->pee_count; ?>人</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect"  class="col-sm-2 control-label"> <i></i></label>
                                    <div class="col-sm-6" style="text-align:right;margin-bottom:45px;font-size:12px;">
                                        <a href="javascript:void(0)" style="font-size:12px;" do="del_pee" v="<?php echo $val->p_id ?>">删除此尿点</a>
                                    </div>
                                </div>
                            </div>
                        </div>




            <?php } } ?>

            <div class="form-group operationBtn" do="addclick" style="position: relative;top:-83px;width:740px;">

                <label for="disabledSelect"  class="col-sm-2 control-label"> <i></i></label>
                <div class="col-sm-10" style="text-align:right;">
                    <a href="javascript:void(0);" style="font-size:12px;" class=" control-label goon" id="addPee">继续添加尿点</a>
                    </div>

            </div>
        </div>
        <!-- 尿点内容结束-->
        <!-- 无尿点推荐语 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'recommend_words', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'recommend_words', array('id' => 'id', 'size' => 60, 'maxlength' => 30, 'class' => 'col-xs-9')); ?>
                <span class="tip"> 最多30字</span>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'eggs', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'eggs',array('rows' => 5, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '开启', '0' => '关闭'), array('separator' => '  ')); ?>
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
        <?php echo $form->hiddenField($model,'open_id',array('id' => 'open_id_1'))?>
        <?php echo $form->hiddenField($model,'nick_name',array('id' => 'nick_name_1'))?>
        <?php echo $form->hiddenField($model,'head_img',array('id' => 'head_img_1'))?>
    </div>
</div>
<script>
    $(function () {
        //通过手机号获取用户信息
        $("#mobile_no").blur(function () {
            var mobileNo = $("#mobile_no").val();
            $.ajax({
                data: "mobileNo=" + mobileNo,
                url: "/userTag/getUserInfo?mobileNo="+mobileNo,
                type: "get",
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.error == undefined) {
                        $("#open_id").val(data.openId);
                        $("#nick_name").val(data.nickname);
                        $("#head_img").html("<img src="+data.photoUrl+" >");
                        $("#open_id_1").val(data.openId);
                        $("#nick_name_1").val(data.nickname);
                        $("#head_img_1").val(data.photoUrl);
                    } else {
                        $("#open_id").val('');
                        $("#nick_name").val('');
                        $("#head_img").val('');
                        $("#open_id_1").val('');
                        $("#nick_name_1").val('');
                        $("#head_img_1").val('');
                        alert(data.error);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });

        //通过movieid获取影院名字
        $("#id").blur(function () {
            var movieId = $("#id").val();
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
                        alert(msg);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });
        //点击继续添加尿点
        $("#addPee").click(function (){
            var  str='<div>';
            str += '<label class="col-sm-3 control-label no-padding-right required " for="Pee_id">';
            str += '</label>';
            str += '<div class="niaodian" do="peeList" >';
            str += ' <div class="form-group">';
            str += '<i class="line"></i>';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">尿点时段 <i>*</i></label>';
            str += '  <div class="col-sm-2">';
            str += ' <input class="form-control"  name="start_time[]" type="text" placeholder="" do="notnull" doint ="1" />';
            str += ' </div>';
            str += '<div class="col-sm-4">';
            str += ' <span class="tip"><font style="color:#000;">分钟</font>   (精确到分钟,如：30~35分钟)</span>';
            str += '</div>';
            str += ' </div>';
            str += ' <input  name="pid[]" type="hidden" value=""/> ';
            str += ' <div class="form-group">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">尿点持续时长 <i>*</i></label> ';
            str += ' <div class="col-sm-2">';
            str += ' <input class="form-control"  type="text" name="end_time[]" placeholder="" do="notnull"  doint ="1" />';
            str += ' </div>';
            str += '<div class="col-sm-4">';
            str += ' <span class="tip"><font style="color:#000;">分钟</font></span>';
            str += '</div>';

            str += '</div>';
            str += ' <div class="form-group">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">尿点开始剧情 <i>*</i></label>';
            str += ' <div class="col-sm-6">';
            str += ' <textarea class="form-control" rows="3" name="pee_start_info[]" do="notnull" domax="160"></textarea>';
            str += ' </div> ';
            str += '    <div class="col-sm-3">';
            str += '    <span class="tip"> 最多160字符</span>';
            str += '    </div>';
            str += '    </div> ';
            str += ' <div class="form-group">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">尿点理由 <i>*</i></label>';
            str += ' <div class="col-sm-6">';
            str += ' <textarea class="form-control" rows="3" name="pee_reason[]" do="notnull" domax="86" domin="20"></textarea>';
            str += ' </div>  ' ;
            str += '<div class="col-sm-3">';
            str += '   <span class="tip"> 最少20字符，最多86字符</span>';
            str += ' </div>';
            str += '  </div>';
            str += '  <div class="form-group">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">错过的剧情 <i>*</i></label>';
            str += ' <div class="col-sm-6">';
            str += ' <textarea class="form-control" rows="3" name="pee_error_info[]" do="notnull" ></textarea> ';
            str += '  </div>' ;
            str += ' </div>';
            str += '  <div class="form-group" style="margin-top:20px;">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label">尿尿人数 <i>*</i></label>';
            str += ' <div class="col-sm-2">';
            str += ' <input class="form-control"  type="text" name="base_pee_count[]" placeholder="" value=0  do="notnull" doint ="1"/>';
            str += '  </div>' ;
            str += ' <div class="col-sm-3">';
            str += ' <span class="tip">实际人数：0人</span>';
            str += '  </div> ';
            str += ' </div>';
            str += '  <div class="form-group">';
            str += ' <label for="disabledSelect"  class="col-sm-2 control-label"> <i></i></label>';
            str += ' <div class="col-sm-6" style="text-align:right;margin-bottom:45px;font-size:12px;">';
            str += ' <a href="javascript:void(0)" style="font-size:12px;" do="del_pee" v="">删除此尿点</a>';
            str += '  </div>' ;
            str += ' </div>';
            str+='</div>';
            str+='</div>';

            $(".operationBtn").before(str);
            saveRecommendPee();
        });
        //点击删除尿点
        $("body").delegate('[do=del_pee]','click',function()
        {
            if($("[do=del_pee]").length <= 1){
                alert('最少也要保留一个尿点一个');
                return false;
            }
            var the_a=$(this);
            var v=$(this).attr("v");
            if(v== ""){
                the_a.parent().parent().parent().parent().remove();
                $(".niaodian:last").parent().find(">label").text("内容");
            }else{
                $.post("<?php echo $this->createUrl('pee/delPee')?>","pid="+v+"",function(a){
                    if(a==1){
                        the_a.parent().parent().parent().parent().remove();
                        $(".niaodian:last").parent().find(">label").text("内容");
                        saveRecommendPee();
                    }else{
                        alert(a);
                    }
                })
            }
            saveRecommendPee();
        });

        $("#submit").click(function(){
            //该死的浏览器兼容性
            var ret = true;
            if($('#Pee_is_pee :radio:checked').val() == 1){
                $("[do=notnull]").each(function(){

                    var len = $(this).val().replace(/[^\x00-\xff]/g,'aa').length;
                    if($(this).val() == ''){
                        alert('请检查必填数据');
                        $(this).focus();
                        ret = false;
                        return ret;
                    }
                    if($(this).attr("doint") == 1){
                        if(isNaN($(this).val())){
                            alert('指定必填字段必须为数字');
                            $(this).focus();
                            ret = false;
                            return ret;
                        }
                    }
                    if($(this).attr("domax") != ''){
                        if( len > $(this).attr("domax")){
                            alert('指定字段长度必须小于'+$(this).attr("domax"));
                            $(this).focus();
                            ret = false;
                            return ret;
                        }
                    }
                    if($(this).attr("domin") != ''){
                        if(len < $(this).attr("domin")){
                            alert('指定字段长必须大于'+$(this).attr("domin"));
                            $(this).focus();
                            ret = false;
                            return ret;
                        }
                    }
                });
                if(ret){
                    var list= $('input:radio[name="Pee[recommend_pee]"]:checked').val();
                    if(list == null){
                        ret = false;
                        alert('请选择推荐尿点');
                        return false;
                    }
                }
            }
            if(!$("#id").val()){
                alert('请输入影片id');
                ret = false;
                return ret;
            }
            if(!$("#movie_name").val()){
                alert('请输入影片名称异常');
                ret = false;
                return ret;
            }
            return ret;
        });
        /**
         *
         */
        function saveRecommendPee()
        {
//            var t = $("input[name='start_time']").length;
            var t = $("input[name='start_time[]']").size();
            if(t <1) t=1;
            var str = '';
            for(var i=1;i<=t;i++){
                str +='<input id="Pee_recommend_pee_"'+i+' value="'+i+'"';
                str+= ' type="radio" name="Pee[recommend_pee]">&nbsp;&nbsp;';
                str += '<label for="Pee_recommend_pee_'+i+'">'+i+'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $("[do=recommend_pee_info]").html(str);
        }

    });
</script>


<?php
$this->endWidget();
?>
