<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'videomodule-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); 
$Platform_list =  Yii::app()->params['app_module']['Platform'];
?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->textField($model,'Title',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10','placeholder'=> '请输入模块标题')); ?>
          </div>
        </div>
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Content', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->textField($model,'Content',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10','placeholder'=> '请输入发现的内容')); ?>
          </div>
		  </div>
		 <div class="form-group" >
		  <?php echo $form->labelEx($model, 'Module_Name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->textField($model,'Module_Name',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10','placeholder'=> '请输入发现的标题')); ?>
          </div>
        
        </div>
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Link', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->textField($model,'Link',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10','placeholder'=> '请输入本模块需要连接至的位置')); ?>
          </div>
        </div>	
		
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Label', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->dropDownList($model,'Label',array('观影指南'=>'观影指南','福利猜电影'=>'福利猜电影'),array('size'=>1,'multiple'=>false,'class'=>'col-sm-3 control-label no-padding-right'))?>
          </div>
        </div>
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Pic', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
			 <?php echo $form->fileField($model,'Pic',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-9')); ?>
          </div>
        </div>		
		
		<div class="form-group" >
			 <?php echo $form->labelEx($model, 'Status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
			 <div class="col-sm-9">
			  <?php 
			   $model->Status = 1;
			  echo $form->radioButtonList($model,'Status',array('1'=>'上线','0'=>'下线'),array('separator'=>'&nbsp;','labelOptions'=>array('class'=>'radiolabel')));
			 ?>
			 </div>
		 </div> 
		  
		<div class="form-group">
			 <?php echo CHtml::label('投放日期<span class="required">*</span>','platform', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
		 <div class="col-sm-6">
				<label class="col-sm-1">自</label>
				<div class="col-sm-5">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',[
					'model'=>$model, 'attribute'=>'Start',
					'language'=>'zh_cn',
					'name'=>'Start',
						'options'=>[
							'dateFormat'=>'yy-mm-dd',
							'changeMonth'=>true,
							'changeYear'=>true,
							'yearRange'=>'-2:+2',
							'defaultDate'=>'+0'
						],
					])?>
					</div>
				<label class="col-sm-1">至</label>
				<div class="col-sm-5">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',[
					'model'=>$model, 'attribute'=>'End',
					'language'=>'zh_cn',
					'name'=>'End',
						'options'=>[
							'dateFormat'=>'yy-mm-dd',
							'changeMonth'=>true,
							'changeYear'=>true,
							'yearRange'=>'-2:+2',
							'defaultDate'=>'+0'
						],
					])?>
				</div>
          </div>
		  <div class="col-sm-4">
           
          </div>
		</div>
		<div class="form-group">
			 <?php echo CHtml::label('应用平台<span class="required">*</span>','DiscoveryModule[Platform]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
		 <div class="col-sm-9">
            <?php echo CHtml::checkBoxList('DiscoveryModule[Platform]',$Platform_list,$Platform_list,array('labelOptions'=>array('class'=>'checkbox-inline col-xs-1'),'template'=>"{beginLabel} {input} {labelTitle}  {endLabel}",'separator'=>'&nbsp;')); ?>
          </div>
		</div>
    	
		
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
					创建
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
