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
	<?php echo $form->errorSummary($model); ?>

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
                <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker')); ?>
                <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'movie_id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <input id="movieId" size="60" maxlength="20" class="col-xs-2"  type="text">
            <input id="movieIdButton" type="button" value="搜索">
        </div>
    </div>
    <div class="form-group" >
        <?php echo $form->labelEx($model, '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">
            <?php if(!empty($arrMovies)){?>
                <?php foreach($arrMovies as $k=>$v){?>
                    <div class="col-xs-2" style="border: solid 1px"> <input type="checkbox" name="Vote[movieIds][]" value="<?php echo $v->movie_id."__".$v->movie_name; ?>" checked> <span><?php echo $v->movie_name;?></span></div>
                <?php }?>
            <?php }?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'picture', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">
            <input type="file" value="上传" name="Vote[picture]">参考尺寸：750*500px<br/>
            <img width="80" height="80" src="<?php echo $model->picture;?>">
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'share_picture', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <input type="file" value="上传" name="Vote[share_picture]">正方形，小于32k<br/>
            <img width="80" height="80"  src="<?php echo $model->share_picture;?>">
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
                foreach($sharePlatForm as $k=>$v){
            ?>
                <input type="checkbox" name="Vote[share_platform][]" value="<?php echo $k;?>" <?php if(in_array($k,$arrCheckedSharePlatform)){echo 'checked';}?>><?php echo $v;?>
            <?php
                }
            ?>
        </div>
    </div>



<!-- -->
    <div class="form-group">
        <?php echo $form->labelEx($model, 'type', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div id="movie_name" class="col-xs-9">

        <?php
            switch($model->type){
                case 1:
                    ?>
                    <input id="movie_type" type="radio" class="select_type" checked name="Vote[type]" value="1">单选
                    <?php
                    break;
                case 2:
                    ?>
                    <input id="movie_type" type="radio" class="select_type"  checked name="Vote[type]" value="2">多选
                    <?php
                    break;
                case 3:
                    ?>
                    <input id="movie_type" type="radio" class="select_type"  checked name="Vote[type]" value="3">PK
                    <?php
                    break;
                default:
                    break;
            }
        ?>
        </div>
    </div>

        <?php if($model->type==1){ ?>
        <div id="answer_1">
            <div id="answer_1_div" class="answer_div_class">
                <?php
                foreach($arrAnswerInfo as $k=>$v){?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">选项<?php echo $k+1;?></label>
                        <div class="col-xs-9">
                            <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer1][]" id="Vote_share_title" type="text" value="<?php echo $v->answer; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"></label>
                        <div class="col-xs-9">
                            <div class="col-xs-1" ><?php echo $arrAnswerInfo[$k]->answerRealNum;?>票</div>
                            <div class="col-xs-1"><?php echo $arrAnswerInfo[$k]->answerRatioReally;?>%</div>
                            <div class="col-xs-3">
                                <div class="col-xs-1"></div>
                                <input class="col-xs-5" type="hidden" name="Vote[answerIds][]" value="<?php echo $arrAnswerInfo[$k]->id;?>">
                                <input class="col-xs-5 fillNum" type="text" name="Vote[fill][]" value="<?php echo $arrAnswerInfo[$k]->fill;?>">
                                <input class="realNum" type="hidden" value="<?php echo $arrAnswerInfo[$k]->answerRealNum;?>">
                            </div>
                            <div class="col-xs-1 changeRatio"><?php echo $arrAnswerInfo[$k]->answerRatio;?>%</div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <?php } ?>
        <?php if($model->type ==2){?>
        <div id="answer_2" >
            <div id="answer_2_div"  class="answer_div_class">
                <?php
                foreach($arrAnswerInfo as $k=>$v){?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">选项<?php echo $k+1;?></label>
                        <div class="col-xs-9">
                            <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer1][]" id="Vote_share_title" type="text" value="<?php echo $v->answer; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"></label>
                        <div class="col-xs-9">
                            <div class="col-xs-1" ><?php echo $arrAnswerInfo[$k]->answerRealNum;?>票</div>
                            <div class="col-xs-1"><?php echo $arrAnswerInfo[$k]->answerRatioReally;?>%</div>
                            <div class="col-xs-3">
                                <div class="col-xs-1"></div>
                                <input class="col-xs-5" type="hidden" name="Vote[answerIds][]" value="<?php echo $arrAnswerInfo[$k]->id;?>">
                                <input class="col-xs-5 fillNum" type="text" name="Vote[fill][]" value="<?php echo $arrAnswerInfo[$k]->fill;?>">
                                <input class="realNum" type="hidden" value="<?php echo $arrAnswerInfo[$k]->answerRealNum;?>">
                            </div>
                            <div class="col-xs-1 changeRatio"><?php echo $arrAnswerInfo[$k]->answerRatio;?>%</div>
                        </div>
                    </div>
                <?php }?>
                </div>
        </div>
        <?php }?>
        <?php if($model->type==3){?>
        <div id="answer_3" >
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">选项1</label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer3][]" id="Vote_share_title" type="text" value="<?php echo $arrAnswerInfo[0]->answer; ?>" readonly >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"></label>
                <div class="col-xs-9">
                    <img class="col-xs-2"  src="<?php echo $arrAnswerInfo[0]->picture; ?>">
                    <div class="col-xs-1"><?php echo $arrAnswerInfo[0]->answerRealNum;?>票</div>
                    <div class="col-xs-1"><?php echo $arrAnswerInfo[0]->answerRatioReally;?>%</div>
                    <div class="col-xs-3">
                        <div class="col-xs-1"></div>
                        <input class="col-xs-5" type="hidden" name="Vote[answerIds][]" value="<?php echo $arrAnswerInfo[0]->id;?>">
                        <input class="col-xs-5 fillNum" type="text" name="Vote[fill][]" value="<?php echo $arrAnswerInfo[0]->fill;?>">
                        <input class="realNum" type="hidden" value="<?php echo $arrAnswerInfo[0]->answerRealNum;?>">
                    </div>
                    <div class="col-xs-1 changeRatio"><?php echo $arrAnswerInfo[0]->answerRatio;?>%</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" >选项2</label>
                <div class="col-xs-9">
                    <input size="60" maxlength="200" class="col-xs-10" name="Vote[answer3][]" id="Vote_share_title" type="text" value="<?php echo $arrAnswerInfo[1]->answer; ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"></label>
                <div class="col-xs-9">
                    <img class="col-xs-2" width="80" height="80"  src="<?php echo $arrAnswerInfo[1]->picture; ?>">
                    <div class="col-xs-1"><?php echo $arrAnswerInfo[1]->answerRealNum;?>票</div>
                    <div class="col-xs-1"><?php echo $arrAnswerInfo[1]->answerRatioReally;?>%</div>
                    <div class="col-xs-3">
                        <div class="col-xs-1"></div>
                        <input class="col-xs-5 " type="hidden" name="Vote[answerIds][]" value="<?php echo $arrAnswerInfo[1]->id;?>">
                        <input class="col-xs-5 fillNum" type="text" name="Vote[fill][]" value="<?php echo $arrAnswerInfo[1]->fill;?>">
                        <input class="realNum" type="hidden" value="<?php echo $arrAnswerInfo[1]->answerRealNum;?>">
                    </div>
                    <div class="col-xs-1 changeRatio"><?php echo $arrAnswerInfo[1]->answerRatio;?>%</div>
                </div>
            </div>
        </div>
        <?php }?>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right">说明:</label>
            <div class="col-xs-9">
                （真实投票数、实际投票占比、注水数、注水后占比）
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

        //计算新比率
        $(document).on('blur',".fillNum",function(){
            var all=0;

            $(".fillNum").each(function(i,e){
                var tmp = $(e).val();
                if(tmp>99999){
                    alert('最大限制99999');
                    $(e).val('99999');
                }
                //all=all+parseInt(tmp);
            })
//
//            $(".realNum").each(function(i,e){
//                var tmp2 = $(e).val();
//                all=all+parseInt(tmp2);
//            })

            //todo
            var movie_type = $("#movie_type").val()
            var idName = '';
            switch(movie_type){
                case '1':
                    idName ="answer_1_div";
                break;
                case '2':
                    idName ="answer_2_div";
                break;
                case '3':
                    idName ="answer_3";
                break;
            }
            //获得全部注水数
            var arrFill=[];
            var arrReal=[];
            var arrRatio=[];
            $("#"+idName+"").find(".fillNum").each(function(i,e){
                arrFill.push($(e).val());
            })
            //获得全部真实数
            $("#"+idName+"").find(".realNum").each(function(i,e){
                arrReal.push($(e).val());
            })
            for(x in arrFill){
                all+=parseInt(arrFill[x]);
            }
            for(x in arrReal){
                all+=parseInt(arrReal[x]);
            }
            //alert(arrFill);
            //alert(arrReal);
            //alert(all);
            //计算比率
            $("#"+idName+"").find(".changeRatio").each(function(i,e){
                var ratio = (parseInt(arrFill[i])+parseInt(arrReal[i]))/all;
                ratio = Math.ceil(ratio*100);
                ratio = ratio.toString();
                $(e).text(ratio+"%");
            })
        })


    })

</script>

<?php $this->endWidget(); ?>