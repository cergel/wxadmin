<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");

Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-notice-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
));
//print_r($form->checkBoxList);die;
?>
    <link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'msg_type',array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                          <?php echo $form->radioButtonList($model,'msg_type',array('1' => '优惠活动', '2' => '系统通知', '3' => '电影消息'), array('separator' => ' ')); ?>
                    <?php echo $form->error($model,'msg_type'); ?>
                    </div>
                </div>
            </div>
	</div>
	<div class="row">
             <div class="col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'title',array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
                         
                    <?php echo $form->error($model,'title'); ?>
                    </div>
                </div>
            </div>
	</div>

	<div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'content',array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'content'); ?>
                    </div>
                </div>
            </div>
	</div>

	<div class="row">
            <div class="form-group">
            <?php echo $form->labelEx($model, 'msg_pic', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
                <div class="col-sm-9">
                     <?php if(!$model->isNewRecord) {
                        if (!empty($model->msg_pic)) {
                            ?>
                            <div style="height:100px;width:100px;">
                                <img src="<?php echo $model->msg_pic; ?>" height="100"/>
                            </div>
                        <?php }
                    }?>
                    <div class="col-xs-10">
                        <?php echo $form->fileField($model,'msg_pic',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                        <span class="help-inline col-xs-5">
                        <span class="middle"></span>
                        </span>
                    </div>
                </div>
            </div>
	</div>
        <div class="row">
            <div class="form-group"> 
             <?php echo CHtml::label('推送渠道', '', array('class'=>'col-sm-3 control-label no-padding-right','required'=>true)); ?>
                <div class="col-sm-9">
            <?php echo $form->checkBoxList($model,'channel', MessageNotice::model()->getChannel('list'),array('separator'=>'  ','do'=>"checkbox"));?>
		<?php echo $form->error($model,'status'); ?>
                </div>
            </div>
	</div>
    
     <!--  跳转链接地址  -->
        <?php foreach(MessageNotice::model()->getChannel('list') as $key=>$list){  ?>
            <div class="form-group">
                <?php echo CHtml::label($list.'链接', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php if(!empty($channelUrl[$key])){ ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'链接' ?>" type="text" placeholder="" value="<?php echo $channelUrl[$key];  ?>"  />
                    <?php }else{   ?>
                        <input class="form-control"  name="channelUrl<?php echo $key ?>" id="channelUrl<?php echo $key ?>" channelName="<?php echo $list.'链接' ?>" type="text" placeholder="" value=""  />
                    <?php  } ?>
                </div>
            </div>
        <?php } ?>
     
        <div class="row">
            <div class="form-group"> 
             <?php echo CHtml::label('推送用户', '', array('class'=>'col-sm-3 control-label no-padding-right','required'=>true)); ?>
                <div class="col-sm-9">
                    <?php echo $form->radioButtonList($model,'push_type',array('1' => '全量用户', '0' => '任务ID','2' =>'导入用户openId'), array('separator' => ' ')); ?>
                    
                </div>
            </div>
	</div>
        <div class="row">
            <div class="form-group"> 
             <?php echo CHtml::label('任务ID', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'task_id', array('readonly'=>$bool)); ?>
                </div>
            </div>
	</div>
        <div class="row">
            <div class="form-group"> 
             <?php echo CHtml::label('导入openID', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->fileField($model,'user_file',array('class' => 'col-xs-4')); ?>
                    支持文件格式 .txt .csv
                </div>
                <span>
                    <a href="<?php echo $model->user_file; ?>" target="_blank"><?php if($model->user_file) echo $model->user_file;?></a>
                    <input type="hidden" id="user_file" value="<?php if($model->user_file) echo $model->user_file;?>"/>
                </span>
            </div>
	</div>
     
    
        <div class="row">
            <div class="form-group"> 
             <?php echo CHtml::label('定时推送', '', array('class'=>'col-sm-3 control-label no-padding-right','required'=>true)); ?>
                <div class="col-sm-9">
                    <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'push_date',  array('class'=>'form-control date-timepicker')); ?>
                        <span class="input-group-addon">
                            <i class="fa fa-clock-o bigger-110"></i>
			</span>
                    </div>
                </div>
            </div>
	</div>
        <div class="row">
            <div class="form-group"> 
            <?php echo CHtml::label('', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->checkBox($model,'is_push',array());?>
                    同时推送客户端push
                </div>
            </div>
	</div>

        <div class="row">
            <div class="form-group"> 
              <?php echo $form->labelEx($model,'push_msg',array('size'=>60,'maxlength'=>200,'class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'push_msg',array('size'=>60,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'push_msg'); ?>
                </div>
            </div>
	</div>

        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
                </button>
               
            </div>
        </div>

<?php $this->endWidget(); ?>
<?php
Yii::app()->getClientScript()->registerScriptFile("/assets/js/message_notice.js");
?>
</div><!-- form -->