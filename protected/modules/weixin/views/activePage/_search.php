<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="row">
<div class="col-xs-12">
    <div class="form-group">
        <?php echo $form->labelEx($model,'iActivePageID', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'iActivePageID',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'sName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'sName',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'sTitle',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'sRule', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'sRule',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'sShareTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'sShareTitle',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'sShareContent', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'sShareContent',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'iDeleted', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'iDeleted',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'iCreated', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'iCreated',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'iUpdated', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'iUpdated',array('class' => 'col-xs-10 col-sm-10')); ?>
        </div>
    </div>
    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="submit">
                <i class="ace-icon fa fa-check bigger-110"></i>
                搜索
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