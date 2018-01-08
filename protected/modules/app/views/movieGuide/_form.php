<div style="display: none;">
    <form name="PostForm" id="PostForm">
        <input name="file" id="upload_btn" type="file" />
        <input type="reset">
    </form>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:37
 */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'app-movie-guide-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
));
?>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'movieId', array('class' => 'col-sm-3 control-label no-padding-right')) ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movieId', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'subTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'subTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'coverImg', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <!--   封面   -->
            <div class="col-sm-9">
                <div style="height:200px;width:400px;" class="uploadArea">
                    <img id="cover_img" src="<?php echo isset($model->coverImg) ? $model->coverImg : ''; ?>" width="180" height="130"/>
                    <input class="delImg" type="button" style="display:none;" value="删除" />
                    <input class="uploadImg" type="button" value="选择文件" />
                    <input class="ImgPath" name="MovieGuide[coverImg]" style="display:none;" value="<?php echo isset($model->coverImg) ? $model->coverImg : ''; ?>" />
                </div>
            </div>

        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'link', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'link', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'baseGetCount', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'baseGetCount', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="Active_source_name"><B>分享配置:</B></label>
        </div>
        <!--        分享图标  -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'shareIcon', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;" class="uploadArea">
                    <img id="shareIcon_img" src="<?php echo isset($model->ShareIcon) ? $model->ShareIcon : ''; ?>"
                         width="180" height="130" />
                    <input class="delImg" type="button" style="display:none;" value="删除" />
                    <input class="uploadImg" type="button" value="上传" />
                    <input class="ImgPath" name="MovieGuide[shareIcon]" style="display:none;" value="<?php echo isset($model->shareIcon) ? $model->shareIcon : ''; ?>" />
                    <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于 32K</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'shareTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'shareTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
                <div style="color:#D42124;margin-top:8px;">15字以内</div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'shareDesc', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'shareDesc', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'shareLink', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'shareLink', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '分享平台(仅APP用)', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="row">
            <?php
                if(!$model->isNewRecord){
                    $share = [];
                    $arr = json_decode(($model->sharePlatform), true);
                    foreach($arr as $k => $v){
                        $share[$v] = 1;
                    }
                }
                if (isset(Yii::app()->params['share_platform'])) {
                    foreach (Yii::app()->params['share_platform'] as $k => $v) { ?>
                        <div class="col-xs-2">
                            <input type="checkbox" name="share[]" value="<?php echo $k; ?>" <?php echo isset($share[$k]) ? 'checked' : '' ?> /><?php echo $v; ?>
                        </div>
            <?php }
                } ?>
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

<?php $this->endWidget();
$cs = Yii::app()->getClientScript();
$baseUrl = Yii::app()->baseUrl;
$cs->registerScriptFile($baseUrl . '/assets/js/movieguide.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl .'/assets/js/jquery.form.js',CClientScript::POS_END);
?>
