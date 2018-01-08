
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'movie-poster-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal','autocomplete' => 'off'),
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $form->textField($model,'movie_id',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
             <?php echo $form->labelEx($model, 'poster_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'poster_type',MoviePoster::model()->getPoster(), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('图片', 'url', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                 <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="<?php echo $model->url;?>" height="200" />
                    </div>
                <?php } ?>
                    <span class="help-inline col-xs-5">
								<span class="middle"></span>
                    </span>
                </div>
            </div>
        </div>
        
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
            <?php if ($model->status != '3'){?>
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
					<?php echo $model->isNewRecord ? '创建' : '通过'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
