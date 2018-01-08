<div class="row">
    <div class="col-xs-12">
<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
   $('.date-timepicker').datetimepicker({
            format:\"YYYY-MM-DD HH:mm:ss\"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'active-pageQq-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
                    <?php echo Chtml::label('图片', 'sImages', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                        <?php if ($model->sImages) { ?>
                            <div style="height:200px;width:400px;">
                            <?php if ($model->iId){ ?>
                                <img src="<?php echo Yii::app()->params['Fulishe']['local'] . '/' . $model->iId . '/' . $model->sImages;?>" height="200" />
                            <?php }?>
                            </div>
                        <?php } ?>
                        <div class="col-xs-10">
                            <?php echo $form->fileField($model,'sImages',array('class' => 'col-xs-5'));?>
                            <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸：735*360px</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sContent', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'sContent',array('size'=>60,'maxlength'=>12,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sText', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'sText',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iTag', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->radioButtonList($model,'iTag',Fulishe::getTagList('list'), array('separator' => ' ')); ?>
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <?php // echo $form->labelEx($model, 'iStatus', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php //  echo $form->radioButtonList($model,'iStatus',['0'=>'下线','1'=>'上线'], array('separator' => ' ')); ?>
                    </div>
                </div>
                -->
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'onLineTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-3">
                            <?php echo $form->textField($model, 'onLineTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'offLineTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-3">
                            <?php echo $form->textField($model, 'offLineTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sLinke', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'sLinke',array('class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'iOrder', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'iOrder',array('class' => 'col-xs-10')); ?>
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
