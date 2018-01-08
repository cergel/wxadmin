<div style="display: none;">
    <form name="PostForm" id="PostForm">
        <input name="file" id="upload_btn" type="file" />
        <input type="reset">
    </form>
</div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'app-movie-guide-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
));
?>
<div class="row">
    <div class="col-xs-9">
        <div class="control-group">
            <label class="control-label"><b>消息类型</b></label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value="1" name="message_type" checked="checked"/>
                    营销信息
                </label>

                <!--<label class="radio inline">
                    <input type="radio" value="0" name="message_type"/>
                    其他信息
                </label>-->
            </div>
        </div>

        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>标题</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入消息标题" name="msg_title" class="input-xlarge">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>内容类型</b></label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value="1" name="content_type"/>
                    文本信息
                </label>

                <label class="radio inline">
                    <input type="radio" value="2" name="content_type"/>
                    图文消息
                </label>
            </div>
        </div>
        <div id="images_text_msg_area">
            <div class="control-group">
                <label class="control-label"><b>封面图</b></label>
                <div class="controls">
                    <button class="upload">上传</button>
                    <input type="hidden" name="img_path"/>
                    <span id="img_preview"></span>
                    <p class="help-block"></p>
                </div>
            </div>
            <div class="control-group">
                <!-- Text input-->
                <label class="control-label"><b>链接</b></label>
                <div class="controls">
                    <input type="text" placeholder="请输入图文消息链接" name="msg_link" class="input-xlarge">
                    <p class="help-block"></p>
                </div>
            </div>
        </div>

        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>消息内容</b></label>
            <div class="controls">
                <textarea name="content_text" class="input-xlarge" rows="5"></textarea>
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>推送渠道</b></label>
            <div class="controls share_platform">
                <label class="checkbox inline"><input name="channel[]" type="checkbox" value="3" disabled>微信电影票</label>
                <label class="checkbox inline"><input name="channel[]" type="checkbox" value="26" disabled>手Q电影票</label>
                <label class="checkbox inline"><input name="channel[]" type="checkbox" value="9">Android</label>
                <label class="checkbox inline"><input name="channel[]" type="checkbox" value="8">iOS</label>
            </div>
            <p class="help-block"></p>
        </div>
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>定时推送</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入推送时间" name="push_time" class="input-xlarge date-timepicker">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>用户标签筛选</b></label>
            <div class="controls">

                <input type="text" id="tag_selector" placeholder="按照用户标签筛选" name="tag_selector"
                       class="input-xlarge required">
                <div class="well">
                    <div class="control-group">
                        <label class="control-label">性别</label>
                        <!-- Multiple Checkboxes -->
                        <div class="controls">
                            <!-- Inline Checkboxes -->
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="男">
                                男
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="女">
                                女
                            </label>
                        </div>

                    </div>

                    <div class="control-group">
                        <label class="control-label">婚否</label>

                        <!-- Multiple Checkboxes -->
                        <div class="controls">
                            <!-- Inline Checkboxes -->
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="已婚">
                                已婚
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="单身">
                                单身
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="未婚非单身">
                                未婚非单身
                            </label>
                        </div>

                    </div>

                    <div class="control-group">
                        <label class="control-label">是否有孩子</label>

                        <!-- Multiple Checkboxes -->
                        <div class="controls">
                            <!-- Inline Checkboxes -->
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="有孩子">
                                有
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="无孩子">
                                无
                            </label>
                            <?php foreach($tagList as $val): ?>
                            <?php echo $val['tag_name']; ?>
                            <?php endforeach;?>
                            
                
                        </div>

                    </div>

                    <div class="control-group">
                        <label class="control-label">年龄</label>

                        <!-- Multiple Checkboxes -->
                        <div class="controls">
                            <!-- Inline Checkboxes -->
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="70后">
                                70后
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="80后">
                                80后
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="85后">
                                85后
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="90后">
                                90后
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="95后">
                                95后
                            </label>
                        </div>

                    </div>


                    <div class="control-group">
                        <label class="control-label">生活特征</label>

                        <!-- Multiple Checkboxes -->
                        <div class="controls">
                            <!-- Inline Checkboxes -->
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="看过动漫">
                                看过动漫
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="玩过游戏">
                                玩过游戏
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="taglist[]" value="游戏支付过">
                                游戏支付过
                            </label>
                        </div>

                    </div>
                </div>
                <div class="controls">
                    <input type="text" placeholder="按照地区筛选,留空为全部地区" name="zone_selector" class="input-xlarge required">
                    <p class="help-block"></p>
                </div>
            </div>


        </div>
        <div class="col-xs-3">
            <div class="control-group">
                <a href="#" id="save_btn" class="btn btn-primary">保存配置</a>
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

    $cs->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
    $cs->registerCssFile("/assets/css/jquery.tagit.css");
    $cs->registerCssFile("/assets/css/tagit.ui-zendesk.css");
    $cs->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
    $cs->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/assets/js/jquery.form.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/assets/js/jquery-ui.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/assets/js/tag-it.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl . '/assets/js/message.js', CClientScript::POS_END);
    ?>
