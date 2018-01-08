<?php
Yii::app()->clientScript->registerScript('form', "
    function update_form() {
 		var thisid = $('#Version_itype :radio:checked').val();
		if(thisid){
			$('.form-group').hide();
			$('[isall=1]').show();
			$('[itype'+'='+thisid+']').show();
		}else{
			$('#Version_itype_0').attr('checked','checked');
			$('#Version_itype_0').click();
		}

    }


    $('#Version_itype :radio').click(function () {
        update_form();
    });

    update_form();
	var isnew = '". $model->isNewRecord."';
	if(!isnew){
		$('#Version_itype :radio').attr('disabled','disabled');
	}


");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'version-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>

        <div class="form-group" isall=1>
            <?php echo $form->labelEx($model, 'itype', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model, 'itype', array('9' => 'Android', '8' => 'IOS'), array('separator' => "   ")); ?>
            </div>
        </div>
        <div class="form-group"  id="version_type_list" isall=1>
            <label for="" class="col-sm-3 control-label no-padding-right required" id="">升级到该版本</label>
            <div class="col-sm-5">
                <?php
                    $params = ['size' => 128, 'maxlength' => 128, 'class' => 'col-xs-10',  'value'=>'0.0.0'];
//                    if($model->version_type ==0 ){
//                        $params['readonly'] = 'true';
//                    }
                    if($model->version){
                        $params['value'] = $model->version;
                    }
                    echo $form->textField($model, 'version', $params); ?>
            </div>
        </div>
        <?php if (!empty($model->id)) { ?>
            <?php echo $form->hiddenField($model, 'itype'); ?>
        <?php } ?>
        <div class="form-group" isall=1>
            <?php echo $form->labelEx($model, 'status', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model, 'status', array('1' => '发布', '0' => '下线'), array('separator' => "   ")); ?>
            </div>
        </div>
        <div class="form-group" isall=1>
            <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('size' => 40, 'maxlength' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall=1>
            <?php echo Chtml::label('图片', 'Version_path', array('required' => false, 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div>
                        <?php echo $model->img; ?>
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'img', array('class' => 'col-xs-5')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">..。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" isall=1>
            <?php echo $form->labelEx($model, 'description', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" itype=9>
            <?php echo Chtml::label('安装包', 'Version_path', array('required' => true, 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div>
                        <?php echo $model->path; ?>
                    </div>
                    <!--
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_version/<?php echo $model->path; ?>" height="200" />
                    </div>
                    -->
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'path', array('class' => 'col-xs-5')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">..。</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" itype=8>
            <?php echo $form->labelEx($model, 'path', array('required' => true, 'class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'path', array('size' => 200, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall=1>
            <?php echo $form->labelEx($model, 'forceUpdate', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->radioButtonList($model, 'forceUpdate', array(0 => '否', 1 => '是'), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group"  id="version_type_list" isall=1>
        <label for="Version_version_type" class="col-sm-3 control-label no-padding-right required" id="version_type_label"><?php if(empty($model->version) || $model->itype == 9) echo 'Android'; else echo 'IOS'?></label>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model, 'version_type', array('全部版本', '版本号小于等于', '版本号大于等于', '指定版本号'), array('separator' => "   ")); ?>
            </div>
            <div class="col-sm-5">
                <?php
                $params = ['size' => 128, 'maxlength' => 128, 'class' => 'col-xs-10',  'value'=>'0.0.0', 'id'=>'version_match'];
                if($model->version_type ==0 ){
                    $params['readonly'] = 'true';
                }
                if($model->version_match){
                    $params['value'] = $model->version_match;
                }
                echo $form->textField($model, 'version_match', $params); ?>
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
<script>
    $(function () {
        $("#Version_itype_0").click(function () {
            $("#version_type_label").html("Android")
        })
        $("#Version_itype_1").click(function () {
            $("#version_type_label").html("IOS")
        })


        $("#Version_version_type").change(function () {
            if($(this).children('option:selected').val() == 0){
                $("#version_match").attr("readonly",true);
                $("#version_match").val('0.0.0');
            }else{
                $("#version_match").attr("readonly",false);
                $("#version_match").val('');
            }
        })
    })
</script>
