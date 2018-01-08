<form id='_fileForm' enctype='multipart/form-data' style="display:none">

    <input name="file" id="fileupload" type="file"/>
</form>

<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:54
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('form', "
    function update_form() {
 		var thisid = $('#DaySign_iType :radio:checked').val();
		if(thisid){
			$('.form-group').hide();
			$('[isall=1]').show();
			$('[itype'+thisid+'='+thisid+']').show();
		}
		else{
			$('#DaySign_iType_0').attr('checked','checked');
			$('#DaySign_iType_0').click();
		}
    }

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
         $(this).prev().focus();
    });

    $('#DaySign_iType :radio').click(function () {
        update_form();
    });

    update_form();
    var isnew = '" . $model->isNewRecord . "';
	if(!isnew){
		$('#DaySign_iType :radio').attr('disabled','disabled');
	}


    $('#DaySign_iLinkType :radio').click(function () {
        var ilinkType = $('#DaySign_iLinkType :radio:checked').val();
            if(ilinkType!=-1){
                $('#DaySign_iAddress').removeAttr('disabled');
            }else{
                 $('#DaySign_iAddress').attr('disabled','disabled');
            }
    });
    if(isnew){
        $('#DaySign_iAddress').attr('disabled','disabled');
    }

    if(!isnew){
        var ilinkType = $('#DaySign_iLinkType :radio:checked').val();
        if(ilinkType==-1){
            $('#DaySign_iAddress').attr('disabled','disabled');
        }
        $(':submit').click(function(){
            var address = $('#DaySign_iAddress').val();
            var linkType = $('#DaySign_iLinkType :radio:checked').val();
            if(!address && linkType!=-1) {
                $('#DaySign_iLinkType_3').attr('checked','checked');
            }
	    });
    }
");

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'app-day-sign-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
)); ?>


<div class="row">
    <div class="col-xs-12">
        <!-- 是否是控件点过来的日期 -->
        <?php if (!empty($_GET['iID'])) { ?>
            <div class="form-group">
                <center><h3>当前创建日期: <?php echo $_GET['iID']; ?></h3></center>
                <input type="text" value="<?php echo $_GET['iID']; ?>" name="DaySign[iID]">
            </div>
        <?php } ?>

        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>

        <!--定义标题标签和输入框-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义首页入口-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iNimage', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) {
                    if (!empty($model->iNimage)) {
                        ?>
                        <div style="height:200px;width:400px;">
                            <img src="/uploads/app_daySign/<?php echo $model->iNimage; ?>" height="200"/>
                        </div>
                    <?php }
                } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'iNimage', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸320px*120px</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iImage', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) {
                    if (!empty($model->iImage)) {
                        ?>
                        <div style="height:200px;width:400px;">
                            <img src="/uploads/app_daySign/<?php echo $model->iImage; ?>" height="200"/>
                        </div>
                    <?php }
                } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'iImage', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸320px*120px</span>
                    </span>
                </div>
            </div>
        </div>
        <!-- 推送类型  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iType', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model, 'iType', DaySign::getTypeList(), array('separator' => ' ')); ?>
            </div>
        </div>
        <?php if (!empty($model->iID)) { ?>
            <input type="hidden" id="DaySign[iType]" name="DaySign[iType]" value=<?php echo $model->iType; ?>>
        <?php } ?>
        <!--定义背景图-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iBackground', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <?php if (strstr($model->iBackground, 'http')) { ?>
                            <img src="<?php echo $model->iBackground; ?>" height="200"/>
                        <?php } else { ?>
                            <img src="/uploads/app_daySign/<?php echo $model->iBackground; ?>" height="200"/>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'iBackground', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸320px*120px,最大250K</span>
                    </span>
                </div>
            </div>
        </div>
        <!--定义台词文本-->
        <div class="form-group" itype1="1">
            <?php echo Chtml::label('台词文本', 'iContent', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iContent', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义选自电影-->
        <div class="form-group" itype1="1">
            <?php echo Chtml::label('选自电影', 'iFfilm', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iFfilm', array('size' => 60, 'maxlength' => 100, 'class' => 'col-xs-2')); ?>
            </div>
        </div>


        <!--定义推荐语-->
        <div class="form-group" itype2="2">
            <?php echo Chtml::label('推荐语', 'iPushtext', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iPushtext', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义推荐电影-->
        <div class="form-group" itype2="2">
            <?php echo Chtml::label('推荐电影', 'iPushfilm', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iPushfilm', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义导演名称-->
        <div class="form-group" itype2="2">
            <?php echo Chtml::label('导演名称', 'iDirectname', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iDirectname', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义影人语录-->
        <div class="form-group" itype3="3">
            <?php echo Chtml::label('影人语录', 'iActorsay', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iActorsay', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义影人名称-->
        <div class="form-group" itype3="3">
            <?php echo Chtml::label('影人名称', 'iActorName', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iActorName', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义影人身份-->
        <div class="form-group" itype3="3">
            <?php echo Chtml::label('影人身份', 'iActorprofession', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iActorprofession', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义代表作品-->
        <div class="form-group" itype3="3">
            <?php echo Chtml::label('代表作品', 'iProduct', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iProduct', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义标题文字-->
        <div class="form-group" itype4="4">
            <?php echo Chtml::label('标题文字', 'iTitlecontent', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iTitlecontent', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义描述内容-->
        <div class="form-group" itype4="4">
            <?php echo Chtml::label('描述内容', 'iDecribe', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iDecribe', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义内容文本-->
        <div class="form-group" itype5="5">
            <?php echo Chtml::label('内容文本', 'iContenttext', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iContenttext', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义电影年代-->
        <div class="form-group" itype1="1" itype2="2">
            <?php echo Chtml::label('电影年代', 'iMovietime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iMovietime', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义音频播放-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iMusic', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php // echo ($form->textField($model, 'iMusic', array('size' => 1, 'maxlength' => 200, 'class' => 'col-xs-10'))); ?>
                <?php
                if (empty($model->iMusic)) {
                    echo ' <input size="1" class="col-xs-10" name="DaySign[iMusic]" id="DaySign_iMusic"
                       type="hidden" />';
                    echo '<div id="audio_player"></div>';
                    echo '<button class="btn btn-info" id="upload_audio">上传</button>';
                } else {
                    echo ' <input size="1" class="col-xs-10" name="DaySign[iMusic]" id="DaySign_iMusic"
                       type="hidden" value="' . $model->iMusic . '" />';
                    echo '<div id="audio_player"><audio autoplay="autoplay" loop="loop" controls="controls" src="/uploads/Assets/' . $model->iMusic . '"></audio></div>';
                    echo '<button class="btn btn-info" id="upload_audio">更改</button>';
                }
                ?>


            </div>
        </div>
        <!--定义音频描述-->
        <div class="form-group" isall="1">
            <?php echo Chtml::label('音频描述', 'iMdecribe', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'iMdecribe', array('size' => 60, 'maxlength' => 500, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!--定义点赞注水-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iSupport', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iSupport', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 链接类型  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iLinkType', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php
                if ($model->isNewRecord) {
                    $model->iLinkType = -1;
                    echo $form->radioButtonList($model, 'iLinkType', DaySign::getLinkTypeList(), array('separator' => ' '));
                } else {
                    echo $form->radioButtonList($model, 'iLinkType', DaySign::getLinkTypeList(), array('separator' => ' '));
                }
                ?>
            </div>
        </div>
        <!--定义跳转链接-->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iAddress', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'iAddress', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>

        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sOtherDay', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sOtherDay', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sVideo', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sVideo', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 视频链接类型  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sVideoType', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model, 'sVideoType', DaySign::getVideoType('0'), array('separator' => '  ')); ?>
            </div>
        </div>

        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareShowTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sShareShowTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sShareTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareSecondTitle', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sShareSecondTitle', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareUrl', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'sShareUrl', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
            </div>
        </div>

        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareImg', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) {
                    if (!empty($model->sShareImg)) {
                        ?>
                        <div style="height:200px;width:400px;">
                            <img src="/uploads/app_daySign/<?php echo $model->sShareImg; ?>" height="200"/>
                        </div>
                    <?php }
                } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model, 'sShareImg', array('size' => 60, 'maxlength' => 200, 'class' => 'col-xs-10')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸没写</span>
                    </span>
                </div>
            </div>
        </div>


        <!--定义提交按钮-->
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '提交' : '保存'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    取消
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/assets/js/daysign.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/assets/js/jquery.form.js', CClientScript::POS_END);
$this->endWidget();
?>
