<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.

	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal','autocomplete' => 'off')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sEmail', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sEmail',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sUserName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sUserName',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sNickName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <input type="text" style="display:none;" /><!-- 防止chrome自动填充 -->
                <?php echo $form->textField($model,'sNickName',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'sPassword', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">

                <input type="text" style="display:none;" /><!-- 防止chrome自动填充 -->
                <?php echo $form->passwordField($model,'sPassword',array('value' => '', 'size'=>60,'maxlength'=>100,'class' => 'col-xs-10 col-sm-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="User_sPassword">用户分组 <span class="required">*</span></label>
            <div class="col-sm-9">
                <select class="col-xs-5 col-sm-5"  name="User[sGroupId]">
                    <option value="0">超级管理员</option>
                    <?php $groupList = UserGroup::model()->findAll();
                    foreach ($groupList as $groupValue) {
                        $selected = $model->sGroupId == $groupValue->id ? 'selected' : '';
                        echo '<option '.$selected.' value="' . $groupValue->id . '">' . $groupValue->groupName . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存' ; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div><!-- /.col -->
</div>
<?php $this->endWidget(); ?>
