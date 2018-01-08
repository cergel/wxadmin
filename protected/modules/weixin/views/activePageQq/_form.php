<div class="row">
    <div class="col-xs-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'active-pageQq-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'link', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'link',array('class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('背景图', 'ActivePage_pic', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                        <?php if ($model->pic) { ?>
                            <div style="height:200px;width:400px;">
                            <?php if ($model->id){ ?>
                                <img src="<?php echo Yii::app()->params['active_page_for_QQ']['target_url'] . '/' . $model->id . '/images/' . $model->pic;?>" height="200" />
                            <?php }else { ?>
                                <img src="<?php echo Yii::app()->params['active_page_for_QQ']['template_img'] . '/title.png' ;?>" height="200" />
                            <?php }?>
                            </div>
                        <?php } ?>
                        <div class="col-xs-10">
                            <?php echo $form->fileField($model,'pic',array('class' => 'col-xs-5'));?>
                            <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸：640X775px</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shareTitle', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'shareTitle',array('class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('分享图片', 'sharePic', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-xs-9">
                        <?php if ($model->sharePic) { ?>
                            <div style="height:200px;width:400px;">
                            <?php if ($model->id){ ?>
                                <img src="<?php echo Yii::app()->params['active_page_for_QQ']['target_url'] . '/' . $model->id . '/images/' . $model->sharePic;?>" height="200" />
                            <?php }else { ?>
                                <img src="<?php echo Yii::app()->params['active_page_for_QQ']['template_img'] . '/logo.png' ;?>" height="200" />
                            <?php }?>
                            </div>
                        <?php } ?>
                        <div class="col-xs-10">
                            <?php echo $form->fileField($model,'sharePic', array('class'=>'col-xs-5'));?>
                            <span class="help-inline col-xs-5">
								<span class="middle">请上传小于512kb的图片文件</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shareContent', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textArea($model,'shareContent',array('rows' => 5, 'class' => 'col-xs-10')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-xs-offset-3 col-xs-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存' ; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
