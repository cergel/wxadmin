
<?php 
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD\"
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
        $('[do=del_movie_poster]').click(function()
        {
        	var the_a=$(this);
            var rootid=$(this).attr("rootid");
            var movie_id=$(this).attr("movieid");
            $.post("/moviePoster/deleteAll","rootid="+rootid+'&movie_id='+movie_id,function(a){
                if(a=='ok'){
                    the_a.parent().parent().remove();
                }
            })
            
        });
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
		<div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $model->id; ?>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'movie_id'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->movie_id; ?>
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
        
        <?php if (!$model->isNewRecord){
        	?>
        	<div class="page-header">
			</div>
        	
        	<?php 
        	foreach ($moviePoster as $key=>$posterType)
        	{
        ?>
	        <div class="form-group">
	            <?php echo Chtml::label(!empty(Yii::app()->params['movie_img_type'][$key]['cn_name'])?Yii::app()->params['movie_img_type'][$key]['cn_name']:'无', 'url', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
	             <div class="col-sm-9" do="sContent">
	            	<?php foreach ($posterType as $value){
	            		?>
	            		<div sContent="textarea" style="position:relative;float:left;">
	            		<div style="height:100px;" class="col-xs-10">
	                      <a target ='_blank' href="<?php echo $value['url']; ?>" style="float:left;display:block;"><img src="<?php echo $value['url'];?>" height="100" /></a>
	                        <a href="javascript:viod()" do="del_movie_poster" posterid="<?php echo $value['id']?>" movieid="<?php echo $value['movie_id']?>" rootid="<?php echo $value['root_id'];?>" style="float:left;position: absolute;top:84px;display:block;width:17px;height:15px;right:-8px;background:url(/assets/img/del.jpg);"></a>
	                    </div>
						</div>
						<?php }?>
	            </div>
	        </div>
        <?php  } ?>
        <div class="page-header" style="background:none;">
        	 <label for="url" class="col-sm-3 control-label no-padding-right"></label>
		    <a class="" href="/moviePoster/create/<?php echo $model->movie_id; ?>" style="display:inner-block;font-size:20px;background-color:#fff;margin-left:18px;color:#87b87f;">新增图片</a>
			</div>
        <?php }?>
         
         
        
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
<?php $this->endWidget(); ?>
