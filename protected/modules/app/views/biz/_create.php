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

"); ?>
<div style="display: none;">
    <form name="PostForm" id="PostForm">
        <input name="file" id="upload_btn" type="file"/>
        <input type="reset">
    </form>
</div>
<div class="alert" role="alert" id="alert"></div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'app-movie-guide-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
));
?>
<div class="row">
    <div class="col-xs-5">
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>影片ID (必填)</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入影片ID" name="movieId" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>

        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>开始时间 (必填)</b></label>
            <div class="controls">
                <input type="text" placeholder="请确认开始时间" name="start" class="date-timepicker input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>结束时间 (必填)</b></label>
            <div class="controls">
                <input type="text" placeholder="请确认结束时间" name="end" class="date-timepicker input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><b>背景图 (必填)</b> (1125*2001 px 小于250kb)</label>
            <div class="controls background_img">
                <button name="button" class="btn upload" data-type="cover_icon">上传</button>
                <span class="img_prview"></span>
                <input type="hidden" name="picurl"/>
                <p class="help-block"></p>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label"><b>高斯模糊图 (必填)</b> (1125*2001 px 小于250kb)</label>
            <div class="controls background_img_blur">
                <button name="button" class="btn upload" data-type="cover_icon">上传</button>
                <span class="img_prview"></span>
                <input type="hidden" name="picurl"/>
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>观影秘笈入口图 (可选)</b>(870*450 px 小于100kb)</label>
            <div class="controls guide_pic">
                <button name="button" class="btn upload" data-type="cover_icon">上传</button>
                <button class="btn btn-danger del_picurl">删除</button>
                <span class="img_prview"></span>
                <input type="hidden" name="picurl"/>
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>投放渠道 (必填)</b></label>
            <div class="controls platform">
                <label class="checkbox inline"><input name="platform[]" type="checkbox" value="8">iOS</label>
                <label class="checkbox inline"><input name="platform[]" type="checkbox" value="9">Android</label>
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label">8秒猜电影</label>
            <div class="controls">
                <input type="text" value="wxmovie://game/super8" name="super8" class="input-xlarge">
                <p class="help-block"></p>
            </div>
            <label class="control-label">8秒猜电影入口图 <b>（上传入口图链接才生效）</b></label>
            <div class="controls super8_pic">
                <button name="button" class="btn upload" data-type="cover_icon">上传</button>
                <button class="btn btn-danger del_picurl">删除</button>
                <span class="img_prview"></span>
                <input type="hidden" name="picurl"/>
                <p class="help-block"></p>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><b>推荐 (可选)</b></label>
            <div class="controls platform">
                <button class="btn btn-success add_recommend">新增推荐位</button>
            </div>
            <div class="tmp_card">
                <div class="controls well" id="recommend_card" style="position: relative;">
                    <span class="pagenum"
                          style="position: absolute;color: #cccccc;; font-weight :bold;font-size:xx-large;width: 30px;height: 30px;top:10px;right: 10px"
                          ;></span>

                    <div class="controls avatar">
                        <label class="control-label"><b>头像</b>(必填) (108px 108px 小于10kb)</label>
                        <button name="button" class="upload btn" data-type="cover_icon">上传</button>
                        <span class="img_prview"></span>
                        <input type="hidden" name="picurl" class="recommend_required"/>
                        <p class="help-block"></p>
                    </div>
                    <div class="controls name">
                        <label class="control-label"><b>名称</b>(必填)(最多10个字)</label>
                        <input type="text" placeholder="名称" name="name" class="input-xlarge recommend_required"/>
                        <p class="help-block"></p>
                    </div>
                    <div class="controls remark">
                        <label class="control-label"><b>注释</b>(必填)(最多10个字)</label>
                        <input type="text" placeholder="注释" name="remark" class="input-xlarge recommend_required"/>
                        <p class="help-block"></p>
                    </div>
                    <div class="controls content">
                        <label class="control-label"><b>内容</b>(必填)(最多60个字)</label>
                        <textarea class="input-xlarge recommend_required" name="content"></textarea>
                        <p class="help-block"></p>
                    </div>
                    <button class="btn btn-danger delete_recommend">删除</button>
                </div>
            </div>
            <div class="recommend_card">

            </div>
        </div>


    </div>

    <div class="col-xs-5">
        <div class="control-group preview">

        </div>
        <div class="control-group">
            <a href="#" id="config_btn" class="btn btn-success ">生成配置</a>
            <a href="#" id="save_btn" class="btn btn-primary" disabled="true">保存配置</a>
        </div>
        <div class="control-group">
            <div class="config_json">


            </div>
        </div>
    </div>
</div>

<?php $this->endWidget();
$cs = Yii::app()->getClientScript();
$baseUrl = Yii::app()->baseUrl;
$cs->registerScriptFile($baseUrl . '/assets/js/biz.js?id=' . mt_rand(1000, 99999), CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/assets/js/jquery.form.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/assets/js/jsonFormater.js', CClientScript::POS_END);


?>
