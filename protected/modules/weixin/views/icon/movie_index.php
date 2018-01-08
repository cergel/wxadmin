<?php
$this->breadcrumbs = array(
    $this->module->id,
    '电影票导航'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
?>
<style>
    .icon__1_width {
        width: 160px;
    }

    .icon_height {
        height: 60px;
        width: 1200px;
        line-height: 35px;
    }

    input[type="text"]{
        color: #000000;
    }
    .icon_input_height {
        width: 100px;
        text-align: center;
        height: 35px;
    }

</style>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">

<div class="row" style="height:200px;">
    <div class="col-md-3 icon__1_width" style="height:200px;line-height: 200px;text-align: left;">
        电影票导航图标
    </div>
    <div class="col-md-2">
        <img id="pic" alt="请上传图片,如不上传，使用前台默认导航图标"
             src="<?php echo $uploadFlag == 1 ? \Yii::app()->params['weixin_icon']['final_url'] . '/movieNav.png' : ''; ?>"
             style="height: 160px;width: 120px; border: 1px solid #D5D5D5">
    </div>
    <div class="col-md-3" style="height: 200px;padding-top: 35px;">
        <div id="file_upload">
        </div>
        <div style="width: 320px;text-align: left;">(请上传图片,如不上传，使用前台默认导航图标)</div>
        <div style="width: 320px;text-align: left;">限定尺寸:114px*208px小于<?php echo $movieNavLimit; ?>kb,png格式</div>
    </div>
    <div class="col-md-3" style="height: 200px;padding-top: 35px;">
        <div id="uploadfileQueue" style="height: 50px;">
        </div>
    </div>
</div>

<div class="row" style="height: 40px;">
    <div>

        <div class="col-md-3 icon__1_width">

        </div>
        <div id="removePicDiv" style="display: <?php echo $uploadFlag==1?'block':'none';?>;">
            <div class="col-md-1" style="margin: 0 auto;">
                    <button  id="removePic" style="width: 70px;height: 24px;line-height: 24px;margin: 0 auto;">清除图片</button>
            </div>
            <div class="col-md-5" style="line-height: 40px;">
                (点击清除图片按钮并<span style="color:red">保存</span>后，微信电影票使用默认导航图片)
            </div>
        </div>
    </div>
</div>
<div class="row" style="height: 40px;line-height: 40px;">
    <div class="col-md-2 icon__1_width">文案内容</div>
    <div class="col-md-2" style="width: 120px; text-align: center">影片</div>
    <div class="col-md-2" style="width: 120px; text-align: center">影院</div>
    <div class="col-md-2" style="width: 120px; text-align: center">发现</div>
    <div class="col-md-2" style="width: 120px; text-align: center">我的</div>
</div>
<div class="row icon_height">
    <div class="col-md-2 icon__1_width"></div>
    <div class="col-md-2" style="width: 120px;"><input class="icon_input_height" id="movieStr" type="text"
                                                       value="<?php echo $movieStr; ?>"/></div>
    <div class="col-md-2" style="width: 120px;"><input class="icon_input_height" id="cinemaStr" type="text"
                                                       value="<?php echo $cinemaStr; ?>"/></div>
    <div class="col-md-2" style="width: 120px;"><input class="icon_input_height" id="findStr" type="text"
                                                       value="<?php echo $findStr; ?>"/></div>
    <div class="col-md-2" style="width: 120px;"><input class="icon_input_height" id="myStr" type="text"
                                                       value="<?php echo $myStr; ?>"/></div>
</div>
<div class="row icon_height">
    <div class="col-md-2 icon__1_width">选中颜色</div>
    <div class="col-md-2">#<input class="icon_input_height" id="checkColor" type="text"
                                  value="<?php echo $checkColor; ?>"/></div>
    <div class="col-md-2" style="">6位，16进制色值，无需#号</div>
</div>
<div class="row icon_height">
    <div class="col-md-2 icon__1_width">未选颜色</div>
    <div class="col-md-2">#<input class="icon_input_height" id="noCheckColor" type="text"
                                  value="<?php echo $noCheckColor; ?>"/></div>
    <div class="col-md-2">6位，16进制色值，无需#号</div>
</div>


<div class="row" style="text-align: center;">
    <div class="col-md-1" style="padding-left: 150px;">
        <button id="save" STYLE="width: 70px;">保存</button>
    </div>
</div>

<script type="application/javascript">
    $(function () {
        var uploadFlag = <?php echo $uploadFlag;?>;//标识是否已经上传图片



        //上传按钮逻辑
        $("#file_upload").uploadify({
            //开启调试
            'debug': false,
            //是否自动上传
            'auto': true,
            'buttonText': '选择图片',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID': 'uploadfileQueue',
            'fileObjName': 'UpLoadFile',
            'uploader': '/weixin/icon/movieUpload',
            'multi': false,
            'fileTypeDesc': '支持的格式：',
            'fileTypeExts': '*.png',
            'fileSizeLimit': '<?php echo $movieNavLimit; ?>KB',
            'removeTimeout': 1,
            //检测FLASH失败调用
            'onFallback': function () {
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess': function (file, data, response) {
                jsonObj = jQuery.parseJSON(data)
                msg = jsonObj.msg;
                url = jsonObj.url;
                succ = jsonObj.success
                alert(msg);
                if (succ == 1) {
                    uploadFlag = 1//标识已经上传了图片
                    $('#pic').attr("src", url + "?r=" + Math.round(Math.random() * 10000))
                    $("#removePicDiv").show();
                }
            }
        });


        //保存按钮
        $("#save").click(function () {
            var movieStr = $("#movieStr").val();
            var cinemaStr = $("#cinemaStr").val();
            var findStr = $("#findStr").val();
            var myStr = $("#myStr").val();

            var checkColor = $("#checkColor").val();
            var noCheckColor = $("#noCheckColor").val();

            $.ajax({
                type: "POST",
                url: "/weixin/icon/movieSave",
                data: {
                    uploadFlag: uploadFlag,
                    movieStr: movieStr,
                    cinemaStr: cinemaStr,
                    findStr: findStr,
                    myStr: myStr,
                    checkColor: checkColor,
                    noCheckColor: noCheckColor
                },
                dataType: "json",
                success: function (data) {
                    alert(data.msg);
                },
                error: function (aaa) {
                    alert(JSON.stringify(aaa))
                }
            });
        })

        //清除按钮
        $("#removePic").click(function () {
            uploadFlag = 0;
            $('#pic').attr("src", '');
            $("#removePicDiv").hide();
            alert('清除完成，点击保存按钮生效');
        })
    });
</script>