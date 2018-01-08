<?php
/* @var $this ActorController */
/* @var $model Actor */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'actor-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    )); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <?php if ($model->getIsNewRecord()) { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model, 'id', array('class' => 'col-xs-4',
                            'maxlength' => "12",
                            'onkeyup' => 'this.value=this.value.replace(/\D/gi,"")'
                        )); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'base_like', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model, 'base_like', array('class' => 'col-xs-4',
                                'maxlength' => "12",
                                'onkeyup' => 'this.value=this.value.replace(/\D/gi,"")')
                        ); ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->id; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'actor_head', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <img style="max-width: 800px" src="<?php echo $actor_head; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name_chs', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->name_chs; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name_eng', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->name_eng; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'like', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $model->like; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'base_like', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model, 'base_like', array('class' => 'col-xs-3',
                            'maxlength' => "12",
                            'onkeyup' => 'this.value=this.value.replace(/\D/gi,"")'
                        )); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'count_like', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <input id="Movie_baseScore" class="col-xs-3" type="text"
                               value="<?php echo $model->base_like + $model->like ?> " disabled>
                    </div>
                </div>
            <?php } ?>
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
                    &nbsp; &nbsp; &nbsp;
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>