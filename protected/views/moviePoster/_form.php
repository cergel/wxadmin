
<?php 
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'movie-poster-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal','autocomplete' => 'off'),
)); ?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<?php echo $form->hiddenField($model,'movie_id'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_id', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            <?php echo $model->movie_id;?>
            </div>
        </div>
        <div class="form-group">
             <?php echo $form->labelEx($model, 'poster_type', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'poster_type',$model->getPoster(), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Chtml::label('图片', 'url', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9" do="moreFile">
            	<div>
                	<a href="javascript:void(0);" id="add_file">增加上传图片</a>
                </div>
                <div class="col-xs-10">
                   <input  class="col-xs-5" type="file" name="url[]">
                </div>
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
<script>
    //多图片上传
    $(function()
    {
        $('#add_file').click(function()
        {
            var obj =$("[do=moreFile]");
            
            var  htmlStr = "";
//              htmlStr ="<div>";
//             	htmlStr += "<div class='col-xs-10'>";
//             	htmlStr += "<input type='file' name='sContent[][img]' class='col-xs-5'/>";
//             	htmlStr += "<span class='help-inline col-xs-5'>";
//             	htmlStr += "<span class='middle'>最佳尺寸: 600px*316px，小于512Kb<a href='javascript:void(0);' do='del_file'>删除</a>。</span>";
//             	htmlStr += "</span>";
//             	htmlStr += "</div>";
//             	htmlStr += "<div class='col-xs-9'>";
//             	htmlStr += "<textarea id=sContent[] sContent='textarea'  class='col-xs-9' name=sContent[] cols='50' rows='3'></textarea>";
//             	htmlStr += "</div>";
//             	htmlStr += "</div>";
            	htmlStr += '<div class="col-xs-10">';
            	htmlStr += '<input  class="col-xs-5" type="file" name="url[]">';
            	htmlStr += "</div>";
            $('[do=moreFile]').append(htmlStr);
        });
     });
</script>
<?php $this->endWidget(); ?>
