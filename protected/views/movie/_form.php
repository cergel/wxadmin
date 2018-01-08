<?php
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'movie-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
));

?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <?php if ($model->getIsNewRecord()) { ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'id',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <?php } ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->id; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movieName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->movieName; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'score', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->score/10; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'baseScore', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'baseScore',array('class' => 'col-xs-3')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'baseScoreCount', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'baseScoreCount',array('class' => 'col-xs-3')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum0', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum0',array('class' => 'col-xs-3')); ?>

                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum0;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum0+$model->scoreFillNum0;?></label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum20', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum20',array('class' => 'col-xs-3')); ?>
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum20;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum20+$model->scoreFillNum20;?></label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum40', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum40',array('class' => 'col-xs-3')); ?>
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum40;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum40+$model->scoreFillNum40;?></label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum60', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum60',array('class' => 'col-xs-3')); ?>
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum60;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum60+$model->scoreFillNum60;?></label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum80', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum80',array('class' => 'col-xs-3')); ?>
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum80;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum80+$model->scoreFillNum80;?></label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'scoreFillNum100', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'scoreFillNum100',array('class' => 'col-xs-3')); ?>
                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;实际人数：<?php echo $model->scoreRealNum100;?>&nbsp;&nbsp;&nbsp;&nbsp;总人数：<?php echo $model->scoreRealNum100+$model->scoreFillNum100;?></label>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'baseWantCount', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'baseWantCount',array('class' => 'col-xs-3')); ?>
            </div>
        </div>
        <div class="form-group">
            <div  style="color: #808080">
                <label class="col-sm-3 control-label no-padding-right" for="Movie_baseWantCount">评分计算公式:</label>
                <div class="col-sm-9">
                <label class="control-label no-padding-right" for="Movie_baseWantCount">(0分*打0分的人数+20分*打20分的人数....+100分*打100分的人数)/(打0分的人数+打20分的人数....+打100分的人数)</label>
                    </div>
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
                &nbsp; &nbsp; &nbsp;
                <div id="fillPre"  class="btn">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    注水预览
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        //注水预览按钮
        var scoreRealNum0 = parseInt(<?php echo $model->scoreRealNum0;?>);
        var scoreRealNum20 = parseInt(<?php echo $model->scoreRealNum20;?>);
        var scoreRealNum40 = parseInt(<?php echo $model->scoreRealNum40;?>);
        var scoreRealNum60 = parseInt(<?php echo $model->scoreRealNum60;?>);
        var scoreRealNum80 = parseInt(<?php echo $model->scoreRealNum80;?>);
        var scoreRealNum100 = parseInt(<?php echo $model->scoreRealNum100;?>);
        var oldScore = <?php echo $model->score;?>;
        $("#fillPre").click(function(){
            var scoreFillNum0 = parseInt($("#Movie_scoreFillNum0").val());
            var scoreFillNum20 = parseInt($("#Movie_scoreFillNum20").val());
            var scoreFillNum40 = parseInt($("#Movie_scoreFillNum40").val());
            var scoreFillNum60 = parseInt($("#Movie_scoreFillNum60").val());
            var scoreFillNum80 = parseInt($("#Movie_scoreFillNum80").val());
            var scoreFillNum100 = parseInt($("#Movie_scoreFillNum100").val());

            var totalScore = 0 + 20*(scoreRealNum20+scoreFillNum20) + 40*(scoreRealNum40+scoreFillNum40) + 60*(scoreRealNum60+scoreFillNum60) + 80*(scoreRealNum80+scoreFillNum80) + 100*(scoreRealNum100+scoreFillNum100);
            var totalNum = scoreRealNum0+scoreFillNum0+scoreRealNum20+scoreFillNum20+scoreRealNum40+scoreFillNum40+scoreRealNum60+scoreFillNum60+scoreRealNum80+scoreFillNum80+scoreRealNum100+scoreFillNum100;
            if(totalScore<0){
                alert("错误:总人数不可以为负");
                return false;
            }
            if(totalNum<=0){
                alert("错误：总人数必须大于0");
                return false;
            }
            if(totalNum==0){
                alert('错误：总人数不可以为0');
                return false;
            }else{
                newScore = Math.ceil(totalScore/totalNum);
            }

            var str = "当前分数："+(oldScore/10)+"  预览分数："+(newScore/10);
            alert(str);
        })
    })

</script>

<?php $this->endWidget(); ?>
