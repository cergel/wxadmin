
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
	'id'=>'movie-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal','autocomplete' => 'off'),
)); ?>
<div class="row">
<script>
    //多图片上传
    $(function()
    {
        $('[do=chras]').keyup(function()
         {
            var chrasCount = $(this).val().length;
            $("#"+$(this).attr("chars")).html("共"+chrasCount+'字');
                    
        });
     });
</script>
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<?php echo $form->hiddenField($model,'id'); ?>
		<?php // echo $form->hiddenField($model,'movie_id'); ?>
		<?php echo $form->hiddenField($model,'movie_no'); ?>
		<div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->id; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->movie_id; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_no', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->movie_no; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name_chs', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name_chs',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name_eng', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name_eng',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name_more', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name_more',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name_pinyin', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name_pinyin',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name_init_pinyin', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'movie_name_init_pinyin',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'director', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'director',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'actor', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'actor',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
         <?php // echo Chtml::label('标签类型', 'tag', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <?php  echo $form->labelEx($model, 'tags', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php $model->tags = explode('/', $model->tags);?>
                <?php echo $form->checkBoxList($model,'tags',MoviePoster::model()->getMovieTagsVersion('tag'), array('separator'=>'  ','template'=>'{input} {label}')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'country', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'country',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'first_show', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'first_show',array('class' => 'col-xs-10 date-timepicker')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'in_short_remark', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'in_short_remark',array('class' => 'col-xs-9','do'=>'chras','chars'=>'in_short_remark_chars')); ?>
                <div id="in_short_remark_chars"></div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'detail', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'detail',array('class' => 'col-xs-9','do'=>'chras','chars'=>'detail_chars')); ?>
                <div id="detail_chars"></div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'age', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'age',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'longs', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'longs',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'version', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                 <?php $model->version = explode('/', $model->version);?>
                <?php  echo $form->checkBoxList($model,'version',MoviePoster::model()->getMovieTagsVersion('version'), array('separator'=>'  ',)); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'coverid', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'coverid',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'score', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'score',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'peomax', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'peomax',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'color', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
           <div class="col-sm-9">
                <?php $model->color = explode('/', $model->color);?>
                <?php echo $form->checkBoxList($model,'color',['黑白'=>'黑白','彩色'=>'彩色'], array('separator'=>'&nbsp;&nbsp; &nbsp; ',)); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'language', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'language',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'source_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo ($model->source_type == 1)?'微影':'灵思'?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'editer_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->editer_id?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'edit_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo date('Y-m-d H:i:s',$model->edit_time)?>
            </div>
        </div>
        <div class="form-group">
         	<?php echo Chtml::label('处理', 'status', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
             <?php echo $form->radioButtonList($model,'status',['0'=>'删除','1'=>'未审核','2'=>'不通过审核','3'=>'通过审核']); ?>
            </div>
        </div>
        
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
					<?php echo $model->isNewRecord ? '创建' : '确认'; ?>
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
