<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 14:17
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'bank-privilege-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <!--选择银行-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'b_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model,'b_id',BankPrivilege::model()->getAllBankInfo(),array('separator' => ' ')); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-success" href="/bankInfo/create">新增银行信息</a>
            </div>
        </div>
        <!--标题-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--简介-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'summary',array('rows'=>6,'maxlength'=>20, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--活动详情描述-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'detail', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'detail',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--办卡链接-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'link', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'link',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'photo', array('required' => true,'class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!empty($model->photo)) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="<?php echo $model->photo;?>" height="100" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'photo',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 正方形，不超过10K。</span>
                    </span>
                </div>
            </div>
        </div>
        <!--开始投放时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'start_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--结束投放时间-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'end_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!--投放城市-->
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

        <!--状态-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '正常', '0' => '隐藏'), array('separator' => ' ')); ?>
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
