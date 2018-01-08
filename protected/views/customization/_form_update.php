<form id='PostForm' name="upload"  enctype="multipart/form-data"  style="display:none">
	<input id="upload_btn" name="file" type="file" />
</form>
<!--载入等待编辑的配置-->
<script type="text/javascript">
<?php
	 $Config_str = str_replace('#CDNPath#',"",$model->Config);
	echo "var Config = {$Config_str}; var SeatId ={$model->SeatId} ;"
	
?>
</script>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'cinema-notification-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal','autocomplete' => 'off','name'=>"BasicInfo")
)); ?>
<div class="row">
    <div class="col-xs-12">
	    <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'MovieId', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'MovieId',array('id'=>'MovieId','size'=>60,'maxlength'=>20,'class' => 'col-xs-6')); ?>
            </div>
        </div>
       <div class="form-group">
			 <?php echo CHtml::label('投放日期<span class="required">*</span>','platform', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
		 <div class="col-sm-6">
				<label class="col-sm-1">自</label>
				<div class="col-sm-5">
					<input type="text" class="date-timepicker" value="<?php echo $model->Start?>" name="Start"  id="Start" />
				</div>
				<label class="col-sm-1">至</label>
				<div class="col-sm-5">
					<input type="text" class="date-timepicker" value="<?php echo $model->End?>"  name="End"  id="End" />
				</div>
          </div>
		</div>
		 
		<div class="form-group">
			<?php echo CHtml::label('选择一个','item[1]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9 form-inline">
				<label class="col-sm-1 control-label">文案</label>
				<div class="col-sm-2">
					<input type="text" class="text field-required selected_text"  id="text1" />
				</div>
				<label class="col-sm-1 control-label">音频</label>
				<div class="col-sm-2">
					<input type="text" class="field field-required selected_Audio"  id="audio1">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('选择二个','item[2]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9 form-inline">
				<label class="col-sm-1 control-label">文案</label>
				<div class="col-sm-2">
					<input type="text" class="text field-required selected_text"  id="text2" />
				</div>
				<label class="col-sm-1 control-label">音频</label>
				<div class="col-sm-2">
					<input type="text" class="field field-required selected_Audio"  id="audio2">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('选择三个','item[3]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9 form-inline">
				<label class="col-sm-1 control-label">文案</label>
				<div class="col-sm-2">
					<input type="text" class="text field-required selected_text"  id="text3" />
				</div>
				<label class="col-sm-1 control-label">音频</label>
				<div class="col-sm-2">
					<input type="text" class="field field-required selected_Audio"  id="audio3">
				</div>
            </div>
        </div>
		<div class="form-group ">
			<?php echo CHtml::label('选择四个','item[4]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9 form-inline">
				<label class="col-sm-1 control-label">文案</label>
				<div class="col-sm-2">
					<input type="text" class="text field-required selected_text"  id="text4" />
				</div>
				<label class="col-sm-1 control-label">音频</label>
				<div class="col-sm-2">
					<input type="text" class="field field-required selected_Audio"  id="audio4">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('可选图标','item[icon]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9">
			
				<div class="col-sm-2">
					<input type="text"  class="field icon" id="icon1">
				</div>
				<div class="col-sm-2">
					<input type="text" class="field icon" id="icon2">
				</div>
				<div class="col-sm-2">
					<input type="text" class="field icon" id="icon3">
				</div>
				<div class="col-sm-2">
					<input type="text"  class="field icon" id="icon4">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('GIF','item[GIF]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9">
			
				<div class="col-sm-2">
					<input type="text" class="field gif"  id="gif1">
				</div>
				<div class="col-sm-2">
					<input type="text" class="field gif"  id="gif2">
				</div>
				<div class="col-sm-2">
					<input type="text" class="field gif"  id="gif3">
				</div>
				<div class="col-sm-2">
					<input type="text" class="field gif"  id="gif4">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('已选座位','item[Selected]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9">
			
				<div class="col-sm-2">
					<input type="text" class="field selected_field"  id="Selected">
				</div>
            </div>
        </div>
		<div class="form-group">
			<?php echo CHtml::label('配置文件','item[config]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
            <div class="col-sm-9">
			
				<div class="col-sm-9">
					<div id="ConfigField"><?php echo $Config_str?></div>
				</div>
            </div>
        </div>
		
        <div class="clearfix form-actions">
            <div class="col-xs-offset-3 col-xs-9">
                
				<a class="btn btn-info" id="config">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    生成配置文件
                </a>
				
				<a class="btn btn-success" id="update_btn" style="display:none">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    更新配置文件
                </a>
				
            </div>
        </div>
    </div>
</div>

<?php 
$this->endWidget();
$baseUrl = Yii::app()->baseUrl;
$cs=Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl .'/assets/css/jsonFormater.css');
$cs->registerCssFile($baseUrl .'/assets/css/bootstrap-datetimepicker.css');
$cs->registerScriptFile($baseUrl .'/assets/js/jsonFormater.js',CClientScript::POS_END);
$cs->registerScriptFile($baseUrl ."/assets/js/date-time/moment.min.js", CClientScript::POS_END);
$cs->registerScriptFile($baseUrl ."/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
$cs->registerScriptFile($baseUrl .'/assets/js/customization.js',CClientScript::POS_END);
$cs->registerScriptFile($baseUrl .'/assets/js/jquery.form.js',CClientScript::POS_END);
 ?>