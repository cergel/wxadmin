<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'actor-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    )); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
