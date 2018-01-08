<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'app-day-push-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
    
    <!-- 是否是控件点过来的日期 -->
        <?php if(!empty($_GET['iId'])){  ?>
         <div class="form-group">
           <center><h3>当前创建日期: <?php echo $_GET['iId']; ?></h3></center>
           <input type="hidden" value="<?php echo $_GET['iId']; ?>" name="AppDayPush[iId]">
        </div>
    <?php } ?>
    
    
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
        	<?php echo $form->labelEx($model, 'sImages', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        	<div class="col-sm-9">
             <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_dayPush/<?php echo $model->sImages;?>" height="200" />
                    </div>
             <?php } ?>
                 <div class="col-xs-10">
                	<?php echo $form->fileField($model,'sImages',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            		<span class="help-inline col-xs-5">
								<span class="middle">小于512Kb。</span>
                    </span>
            	</div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sText', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sText',array('size'=>60,'maxlength'=>1000,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSource', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sSource',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-2')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShareContent', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShareContent',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('分享图片', 'sSharePic', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_dayPush/<?php echo $model->sSharePic;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'sSharePic',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 600px*316px，小于512Kb。</span>
                    </span>
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
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>