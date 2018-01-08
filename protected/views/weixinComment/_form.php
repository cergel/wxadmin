<?php 
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    function update_form() {
        if ($('#DiscoveryBanner_iType :radio:checked').val() == '2') {
            $('.activity-only').show();
            $('#DiscoveryBanner_iCategory option').each(function(){
                if ($(this).val()) {
                    if (parseInt($(this).val()) > 2000) {
                        $(this).removeAttr('disabled').show();
                    } else {
                        $(this).attr('disabled', true).hide();
                    }
                }
            });
        } else if ($('#DiscoveryBanner_iType :radio:checked').val() == '1') {
            $('.activity-only').hide();
            $('#DiscoveryBanner_iCategory option').each(function(){
                if ($(this).val()) {
                    if (parseInt($(this).val()) < 2000) {
                        $(this).removeAttr('disabled').show();
                    } else {
                        $(this).attr('disabled', true).hide();
                    }
                }
            });
        } else {
            $('.activity-only').hide();
            $('#DiscoveryBanner_iCategory option').each(function(){
                 $(this).attr('disabled', true).hide();
            });
        }
    }

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('#DiscoveryBanner_iType :radio').click(function () {
        update_form();
    });

    update_form();
");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        
        <?php echo $form->hiddenField($model,'commentId'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'commentId', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->commentId;?>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'uid'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'uid', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->uid;?>
            </div>
        </div> 
        <?php echo $form->hiddenField($model,'uname'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'uname', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->uname;?>
            </div>
        </div> 
        <?php echo $form->hiddenField($model,'movieId'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movieId', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->movieId;?>
            </div>
        </div> 
        <?php echo $form->hiddenField($model,'movieName'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movieName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->movieName;?>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'movieImg'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movieImg', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
           <img src="<?php echo $model->movieImg;?>" width="400"> 
            </div>
        </div> 
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'showTime', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-3">
           <?php echo $form->textField($model,'showTime',array('class' => 'form-control date-timepicker')); ?>
            </div>
        </div> 
        
        <div class="form-group">
        <?php echo $form->labelEx($model, 'content', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $form->textArea($model,'content',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div> 
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-3">
           <?php echo $form->radioButtonList($model,'status',array('1' => '上线', '0' => '下线'), array('separator' => ' ')); ?>
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
