<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 11:58
 */
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'bank-info-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
            <!--银行编码-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'num', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'num',array('size'=>60,'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <!--银行名称-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>15,'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <!--银行图标-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'image', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php if(!$model->isNewRecord) {
                        if (!empty($model->image)) {
                            ?>
                            <div style="height:200px;width:400px;">
                                <img src="/uploads/bankInfo/<?php echo $model->image; ?>" height="200"/>
                            </div>
                        <?php }
                    }?>
                    <div class="col-xs-10">
                        <?php echo $form->fileField($model,'image',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                        <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸320px*120px</span>
                    </span>
                    </div>
                </div>
            </div>
            <!--状态-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
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