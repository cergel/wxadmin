<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'ad-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
)); ?>


<style>
.qusetionBorder{
    border-bottom: 1px dotted #3CA0D9;
    padding-top: 10px;
    padding-bottom: 10px;
}
</style>

<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <?php echo $form->dropDownList($model, 'num', array('2' => 2, '3' => 3, '4' => 4)); ?>
        </div>
    </div>
    <div class="form-group qusetionBorder">
        <?php echo $form->labelEx($model, 'pic', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-xs-9">
            <img width="200" height="150" src="<?php echo $model->pic;?>" />
            <input type="file" value="上传" name="uploadPic">参考尺寸：690*724px
        </div>
    </div>
    <!--题集区-->
    <div id = "questionSet">
        <div class="qusetionBorder">
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right question-number">问题1：</label>
                <div class="col-xs-9"></div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">标题</label>
                <div class="col-xs-5">
                    <input maxlength="35" class="col-xs-10" type="text" name="QuestionSet[title][]" value="">最多35个字，必填
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项一</label>
                <div class="col-xs-5">
                    <input size="60" maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_1]" value="">最多10个字，必填。
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_1]" value="1" >
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项二</label>
                <div class="col-xs-5">
                    <input size="60" maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_1][]"  value="">最多10个字，必填
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_1]" value="2" >
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项三</label>
                <div class="col-xs-5">
                    <input size="60" maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_1][]"  value="">最多10个字
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_1]" value="3" >
                </div>
            </div>
        </div>
        <div class="qusetionBorder">
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right question-number">问题2：</label>
                <div class="col-xs-9"></div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">标题</label>
                <div class="col-xs-5">
                    <input maxlength="35" class="col-xs-10" type="text" name="QuestionSet[title][]" value="">最多35个字，必填
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项一</label>
                <div class="col-xs-5">
                    <input size="60" maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_2]" value="">最多10个字，必填。
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_2]" value="1" >
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项二</label>
                <div class="col-xs-5">
                    <input  maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_2][]"  value="">最多10个字，必填
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_2]" value="2" >
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right">选项三</label>
                <div class="col-xs-5">
                    <input  maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_2][]"  value="">最多10个字
                </div>
                <div class="col-ms-1">
                    <input type="radio" name="QuestionSet[radio_2]" value="3" >
                </div>
            </div>
        </div>

    </div>




        <div class="form-group" >
        <label class="col-sm-3 control-label no-padding-right"></label>
            <input id="addQuestion" type="button" value="添加问题">
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
<?php $this->endWidget(); ?>

<script>

    //删除按钮逻辑
    $(document).on("click",".delQuestion",function(){
        var par = $(this).parents(".qusetionBorder");
        par.remove();
        //将问题号码重新排列
        var questionNumberLabel = $(".question-number");
        questionNumberLabel.each(function(x,e){
            $(this).text("问题"+(x+1)+"：");
        })
    })

    $(function(){
        //添加按钮逻辑
        $("#addQuestion").on("click",function(){
            var childs = $("#questionSet").children().length;
            if(childs>=20){
                alert("最多只允许添加20个问题");
                return false;
            }
            //待添加进去的模板
            var number = childs+1;
            number = number.toString();
            var html = '<div class="qusetionBorder"> <div class="form-group" > ' +
                '<label class="col-sm-3 control-label no-padding-right question-number">问题'+number+'：</label> ' +
                '<div class="col-xs-9"></div> </div> <div class="form-group" > ' +
                '<label class="col-sm-3 control-label no-padding-right">标题</label> ' +
                '<div class="col-xs-5"> <input  maxlength="35" class="col-xs-10" type="text" name="QuestionSet[title][]">最多35个字，必填 </div> </div> ' +
                '<div class="form-group" > <label class="col-sm-3 control-label no-padding-right">选项一</label> <div class="col-xs-5"> ' +
                '<input maxlength="200" class="col-xs-10" type="text" name="QuestionSet[question_'+number+'][]"> 最多10个字，必填。</div> <div class="col-ms-1"> ' +
                '<input type="radio" name="QuestionSet[radio_'+number+']"  value="1"> </div> </div> <div class="form-group" > ' +
                '<label class="col-sm-3 control-label no-padding-right">选项二</label> <div class="col-xs-5"> ' +
                '<input  maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_'+number+'][]"> 最多10个字，必填</div> ' +
                '<div class="col-ms-1"> <input type="radio" name="QuestionSet[radio_'+number+']"  value="2"> </div> </div> ' +
                '<div class="form-group" > <label class="col-sm-3 control-label no-padding-right">选项三</label> ' +
                '<div class="col-xs-5"> <input  maxlength="10" class="col-xs-10" type="text" name="QuestionSet[question_'+number+'][]"> 最多10个字，必填</div> ' +
                '<div class="col-ms-1"> <input type="radio" name="QuestionSet[radio_'+number+']"  value="3"> </div> </div><div class="form-group" > ' +
                '<label class="col-sm-3 control-label no-padding-right"></label> <input class="delQuestion" type="button" value="删除"> </div></div>';
            $("#questionSet").append(html);
        });

    })
</script>