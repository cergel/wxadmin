<div style="display: none;">
    <form name="PostForm" id="PostForm">
        <input name="file" id="upload_btn" type="file"/>
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
    <div class="col-xs-4">
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>影片ID</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入影片ID" name="movieId" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>

        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>观影秘籍标题</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入观影秘籍标题" name="title" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <!-- Text input-->
            <label class="control-label"><b>观影秘籍副标题</b></label>
            <div class="controls">
                <input type="text" placeholder="请输入观影秘籍副标题" name="subtitle" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>购票按钮</b></label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value="1" name="buy_btn_radio" checked="checked"/>
                    开启
                </label>
                <label class="radio inline">
                    <input type="radio" value="0" name="buy_btn_radio" disabled/>
                    关闭
                </label>
            </div>
        </div>
        <div class="buy_btn_input">
            <div class="control-group">
                <label class="control-label">购票渠道ID</label>
                <div class="controls">
                    <input type="text" name="buy_channelId" placeholder="请输入渠道ID" class="input-xlarge">
                    <p class="help-block"></p>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">购票按钮显示在</label>

                <!-- Multiple Checkboxes -->
                <div class="controls pages_number" id="buy_checkboxs" data-type="buy_btn">


                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>通用按钮</b></label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" value="1" name="general_btn">
                    阅读
                </label>
                <label class="radio inline">
                    <input name="general_btn" type="radio" value="2" name="general_btn">
                    红包
                </label>
                <label class="radio inline">
                    <input type="radio" value="3" name="general_btn">
                    优惠
                </label>
                <label class="radio inline">
                    <input type="radio" value="0" name="general_btn" checked=true>
                    关闭
                </label>
            </div>
        </div>
        <div class="general_btn_input_area" style="display:none">
            <div class="control-group">
                <label class="control-label">客户端链接</label>
                <div class="controls">
                    <input type="text" name="app_link" placeholder="请输入客户端链接" class="input-xlarge">
                    <p class="help-block"></p>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">电影票链接</label>
                <div class="controls">
                    <input type="text" name="wx_link" placeholder="请输入电影票链接" class="input-xlarge">
                    <p class="help-block"></p>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">手Q链接</label>
                <div class="controls">
                    <input type="text" name="mqq_link" placeholder="请输入手Q链接" class="input-xlarge">
                    <p class="help-block"></p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">通用按钮显示在</label>

                <!-- Multiple Checkboxes -->
                <div class="controls pages_number" data-type="general_btn" id="general_checkboxs">


                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>背景音频</b></label>
            <div class="controls">
                <button class="upload" data-type="background_music">上传</button>
                <input type="hidden" name="background_music"/>
                <span id="audio_player"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>封面图</b></label>
            <div class="controls">
                <button class="upload" data-type="cover_icon">上传</button>
                <input type="hidden" name="cover_img" class="required"/>
                <span class="img_prview"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>封面图(大)</b></label>
            <div class="controls">
                <button class="upload" data-type="cover_icon">上传</button>
                <input type="hidden" name="cover_img_large" class="required"/>
                <span class="img_prview"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>领取注水数</b></label>
            <div class="controls">
                <input type="text" name="baseGetCount" placeholder="请输入分享注水数" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>PV注水数</b></label>
            <div class="controls">
                <input type="text" name="basePvCount" placeholder="请输入PV注水数" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>分享标题</b></label>
            <div class="controls">
                <input name="shareTitle" type="text" placeholder="请输入分享的标题" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>分享描述</b></label>
            <div class="controls">
                <input type="text" name="shareDesc" placeholder="请输入分享描述" class="input-xlarge required">
                <p class="help-block"></p>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><b>分享ICON</b></label>
            <div class="controls">
                <button class="upload" data-type="share_icon">上传</button>
                <input type="hidden" name="share_img" class="required"/>
                <span class="img_prview"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><b>分享平台仅对APP有效</b></label>
            <div class="controls share_platform">
                <?php if (isset(Yii::app()->params['share_platform'])): ?>
                    <?php foreach (Yii::app()->params['share_platform'] as $key => $value): ?>
                        <label class="checkbox inline"><input type="checkbox"
                                                              value="<?php echo $key ?>"><?php echo $value ?></label>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        </div>


    </div>
    <div class="col-xs-4">
        <div class="control-group">
            <button class="btn btn-success addpage">新增页面</button>
        </div>
        <div class="control-group">
            <div class="pages">


            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="control-group">
            <a href="#" id="config_btn" class="btn btn-success ">生成配置</a>
            <a href="#" id="save_btn" class="btn btn-primary" disabled="disabled">保存配置</a>
        </div>
        <div class="control-group">
            <div class="config_json">


            </div>
        </div>
    </div>
    <div class="" id="add_mail_div" style="position:fixed;;z-index:2;left:60%;top:25%;right:0;background-color:#00be67; opacity:0.9; -moz-opacity:0.5; filter:alpha(opacity=50);margin:1px 1px;display:none;width:30%;height: 220px;;text-align:center;">
        <div class="control-group">
            <label style="margin-top: 3%; color: white">输入文字：（不超过15个字）</label>
            <textarea class="" style="margin: 5% 5%;width: 80%;" name="m_title" id="m_title"placeholder="请出入锚点内容"></textarea>
            <input type="hidden" name="m_num" value="" id="m_num" />
            <input type="button" class="save_btn" value="保存" onclick="saveDiv()"/>
            <input type="button" class="cancel_btn" value="取消" onclick="closeDiv()"/>
        </div>
    </div>
</div>

<?php $this->endWidget();
$cs = Yii::app()->getClientScript();
$baseUrl = Yii::app()->baseUrl;
$cs->registerScriptFile($baseUrl . '/assets/js/movieguide_new.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/assets/js/jquery.form.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/assets/js/jsonFormater.js', CClientScript::POS_END);


?>
