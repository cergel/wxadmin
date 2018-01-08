<div class="row">
    <div class="col-xs-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'active-pageQq-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
        <?php // echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
                    <?php echo Chtml::label('图片', 'Img', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                            <div style="height:200px;width:400px;">
                                <img src="<?php echo $model['localImg'];?>" height="200" />
                            </div>
                        <div class="col-xs-10">
                        <input type="file" name="Img" id="Img" /> 
                            <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸：640*415px</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                     <?php echo Chtml::label('背景颜色', 'color', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                     <input type="text" name="fulishe[color]" id="color" value="<?php echo $model['color'];?>"/> 
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('上线状态', 'status', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-xs-9">
                        <input type="radio" <?php if (empty($model['status']))echo 'checked="checked"';?> name="fulishe[status]" value="0">下线 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="fulishe[status]" <?php if (!empty($model['status']))echo 'checked="checked"';?> value="1">上线
                        <?php // echo $form->radioButtonList($model,'status',['0'=>'下线','1'=>'上线',], array('separator' => ' ')); ?>
                    </div>
                </div>
                <div class="form-group">
                     <?php echo Chtml::label('公告', 'info', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                     <input type="text" name="fulishe[info]" id="color" maxlength="45" value="<?php echo $model['info'];?>"/> 
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-xs-offset-3 col-xs-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    保存
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                </button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
