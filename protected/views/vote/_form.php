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

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vote-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">', '</div>'); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
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
<<<<<<< Updated upstream
        <?php echo $form->labelEx($model, 'movie_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
=======
    <?php echo $form->labelEx($model, "movie_id", array('class' => 'col-sm-3 control-label no-padding-right')); ?>
>>>>>>> Stashed changes
        <div class="col-xs-9">
            <input id="movieId" size="60" maxlength="20" class="col-xs-2"  type="text">
            <input id="movieIdButton" type="button" value="搜索">
        </div>
    </div>
    <div class="form-group" >
        <?php echo $form->labelEx($model, '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">

        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'picture', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">
            <input type="file" value="上传" name="Vote[picture]">参考尺寸：750*500px
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_picture', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <input type="file" value="上传" name="Vote[share_picture]">正方形，小于32k
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php echo $form->textField($model, 'share_title', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_content', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php echo $form->textField($model, 'share_content', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_platform', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php
                foreach($sharePlatform as $k=>$v){
            ?>
            <input type="checkbox" name="Vote[share_platform][]" value="<?php echo $k;?>" checked="checked"><?php echo $v;?>
            <?php
                }
            ?>
        </div>
    </div>



<!-- -->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">
            <input type="radio" class="select_type" checked name="Vote[type]" value="1">单选
            <input type="radio" class="select_type"  name="Vote[type]" value="2">多选
            <input type="radio" class="select_type"  name="Vote[type]" value="3">PK
        </div>
    </div>


        <div id="answer_1">
            <div id="answer_1_div" class="answer_div_class">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">选项1<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer1][]" id="Vote_share_title" type="text" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" >选项2<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer1][]" id="Vote_share_title" type="text" value="">
                </div>
            </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="Vote_share_title"></label>
                <div class="col-xs-9">
                    <input id="addAnswer1Button" type="button" value="添加选项">
                </div>
            </div>
        </div>
        <div id="answer_2" style="display: none">
            <div id="answer_2_div"  class="answer_div_class">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">选项1<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer2][]" id="Vote_share_title" type="text" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" >选项2<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer2][]" id="Vote_share_title" type="text" value="">
                </div>
            </div>
                </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="Vote_share_title"></label>
                <div class="col-xs-9">
                    <input id="addAnswer2Button" type="button" value="添加选项">
                </div>
            </div>
        </div>
        <div id="answer_3" style="display: none">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">选项1<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer3][]" id="Vote_share_title" type="text" value="">
                    <input type="file" value="上传" name="Vote[answer3Picture][]">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" >选项2<span class="required">*</span></label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer3][]" id="Vote_share_title" type="text" value="">
                    <input type="file" value="上传" name="Vote[answer3Picture][]">
                </div>
            </div>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    $(function(){

        //通过movieid获取影院名字
        $("#movieIdButton").click(function () {
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
                        var html ='<div class="col-xs-2" style="border: solid 1px"> <input type="checkbox" name="Vote[movieIds][]" value="'+movieId+"__"+msg+'">  <span>'+msg+'</span></div>'
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


        //添加选项
        $(".select_type").click(function(){
            var type = $(this).val();
            switch(type){
                case "1":
                    $("#answer_1").show();
                    $("#answer_2").hide();
                    $("#answer_3").hide();
                    break;
                case "2":
                    $("#answer_1").hide();
                    $("#answer_2").show();
                    $("#answer_3").hide();
                    break;
                case "3":
                    $("#answer_1").hide();
                    $("#answer_2").hide();
                    $("#answer_3").show();
                    break;
                default :
                    alert("出错啦！~请重试")
                    return false;
            }
        })

        var getChildNum = function(obj){
            var t= $(obj).parents(".answer_div_class");
            var children = t.children();
            var childrenLen = children.length;
            return childrenLen;
        }

        //type=1的添加选项
        $("#addAnswer1Button").click(function(){
            var childNum = $("#answer_1_div").children().length;
            var nextNum = childNum+1;
            if(nextNum>15){
                alert("选项上限为15个");
                return false;
            }
            var strNextNum = nextNum.toString();
            var html = '<div class="form-group"><label class="col-sm-3 control-label no-padding-right">选项'+strNextNum+'</label> <div class="col-xs-9"> <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer1][]" id="Vote_share_title" type="text" value=""> <input class="del_answer" type="button" value="删除选项"></div> </div>';
            $("#answer_1_div").append(html);
        })

        //type=2的添加选项
        $("#addAnswer2Button").click(function(){
            var childNum = $("#answer_2_div").children().length;
            var nextNum = childNum+1;
            if(nextNum>15){
                alert("选项上限为15个");
                return false;
            }
            var strNextNum = nextNum.toString();
            var html = '<div class="form-group"><label class="col-sm-3 control-label no-padding-right">选项'+strNextNum+'</label> <div class="col-xs-9"> <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer2][]" id="Vote_share_title" type="text" value=""> <input class="del_answer" type="button" value="删除选项"></div> </div>';
            $("#answer_2_div").append(html);
        })

        //删除按钮绑定
        $(document).on('click',".del_answer",function(){
            var t= $(this).parents(".answer_div_class");
            $(this).parents(".form-group").remove();
            var children = t.children();
            var childrenLen = children.length;
            children.each(function(i,n){
                var obj=$(n);
                var num = i+1;
                var htmlStr = "选项"+num;
                obj.children(".control-label").html(htmlStr);
            })

        });


    })

</script>

<?php $this->endWidget(); ?>