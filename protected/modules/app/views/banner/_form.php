<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    function update_form() {
        if ($('#Banner_iType :radio:checked').val() == '1') {
            $('.activity-only').show();
            /*
            $('#Banner_iCategory option').each(function(){
                if ($(this).val()) {
                    if (parseInt($(this).val()) > 2000) {
                        $(this).removeAttr('disabled').show();
                    } else {
                        $(this).attr('disabled', true).hide();
                    }
                }
            });
            */
        } else if ($('#Banner_iType :radio:checked').val() == '2') {
            $('.activity-only').hide();
            /*
            $('#Banner_iCategory option').each(function(){
                if ($(this).val()) {
                    if (parseInt($(this).val()) < 2000) {
                        $(this).removeAttr('disabled').show();
                    } else {
                        $(this).attr('disabled', true).hide();
                    }
                }
            });
            */
        } else {
            $('.activity-only').hide();
            /*
            $('#Banner_iCategory option').each(function(){
                 $(this).attr('disabled', true).hide();
            });
            */
        }
    }

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('#Banner_iType :radio').click(function () {
        update_form();
    });

    update_form();
");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'banner-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sDescription', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sDescription',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('封面', 'Banner_sPicture', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_banner/<?php echo date('Y-m-d', $model->iCreated) . '/' . $model->sPicture;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'sPicture',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 600px*316px，小于512Kb。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sLink', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sLink',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iShowAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iShowAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iHideAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iHideAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iStatus', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iStatus',array('1' => '上线', '0' => '预上线'), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('定向渠道', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->checkbox($model,'iAndroid',array()); ?>
                <?php echo $form->labelEx($model, 'iAndroid', array('class'=>'')); ?>
                <br />
                <?php echo $form->checkbox($model,'iIOS',array()); ?>
                <?php echo $form->labelEx($model, 'iIOS', array('class'=>'')); ?>
                <br />
                <?php echo $form->checkbox($model,'iWX',array()); ?>
                <?php echo $form->labelEx($model, 'iWX', array('class'=>'')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::label('定向城市', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CitySelectorWidget',array(
                        'name' => 'cities',
                        'selectedCities' => $selectedCities
                    )); ?>
                </div>
            </div>
        </div>
        <!--
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iSort', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iSort',array('class' => 'col-xs-1')); ?>
            </div>
        </div>
        -->
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
                        <img src="/uploads/app_banner/<?php echo date('Y-m-d', $model->iCreated) . '/' . $model->sSharePic;?>" height="200" />
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
