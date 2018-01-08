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
            <?php echo $form->labelEx($model, 'nickName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'nickName',array('id'=>'movieId','size'=>60,'maxlength'=>12,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'ucid', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'ucid',array('id'=>'movieId','size'=>60,'maxlength'=>64,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('标签', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="col-xs-10">
                    <?php echo $form->checkBoxList($model,'tag',$model->getStarTag(),array('separator'=>'  ','do'=>"checkbox"));?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'summary',array('id'=>'movieId','size'=>60,'maxlength'=>255,'class' => 'col-xs-10','do'=>"notnull")); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Chtml::label('头像', 'photo', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="<?php echo  $model->photo;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'photo',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 正方形，小于32Kb。</span>
                    </span>
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
        $("#submit").click(function(){
            var returnInfo = true;
            var checboxValueInfo ='';
            $("input:checkbox[name='CommentStar[tag][]']").each(function (){
                if($(this).is(':checked')){
                    checboxValueInfo = $(this).val();
                }
            });
            if(checboxValueInfo == ''){
                alert('标签为必选');
                returnInfo = false;
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
        });
    });
</script>



<?php $this->endWidget(); ?>
