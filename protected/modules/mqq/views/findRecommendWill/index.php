<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/14
 * Time: 15:01
 */
$this->breadcrumbs=array(
    'Recommend Wills'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>发现文章推荐位</h1>
    </div>

<?php

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">标题 <span class="required">*</span></label>            <div class="col-sm-9">
                <input id="title" name="form[title]" rows="6" cols="50" class="col-xs-10"  type="text" value="<?php echo $title;?>">            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">链接 <span class="required">*</span></label>            <div class="col-sm-9">
                <input id="link" name="form[link]"  rows="6" cols="50" class="col-xs-10"  type="text" value="<?php echo $link;?>">            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">上线时间 <span class="required">*</span></label>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <input class="form-control date-timepicker"  name="form[startTime]" id="startTime" type="text" value="<?php echo $startTime;?>">                    <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">下线时间 <span class="required">*</span></label>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <input class="form-control date-timepicker"  name="form[endTime]" id="endTime" type="text" value="<?php echo $endTime;?>">                    <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right required" for="RecommendWill_movie_id">图片 <span class="required">*</span></label>            <div class="col-sm-9">
                <img width="200" height="150" src="<?php echo $pic;?>">
                <input  name="UpLoadFile"    type="file" >            </div>

        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    保存
                </button>
            </div>
        </div>


    </div>
</div>
<?php $this->endWidget(); ?>
