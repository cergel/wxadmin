<?php
$this->breadcrumbs = array(
    $this->module->id,
    '加载动效'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-md-3">加载动效图</div>
    <div class="col-md-2"><img id="pic" alt="请上传图片"  src="<?php echo $pic==1?Yii::app()->params['weixin_icon']['final_url'].'/loading.gif':''; ?>" style="height: 160px;width: 160px; border: 1px solid #D5D5D5"></div>
    <div class="col-md-2">
        <div id="file_upload">
        </div>
        <div id="uploadfileQueue" style="padding: 3px;">
        </div>
    </div>
    <div class="col-md-2">最佳尺寸：180px*180px,小于30Kb</div>
</div>
<div class="row">
    <div class="col-md-2">加载动效文案</div>
    <div class="col-md-4"><input type="text" id="word" style="width: 350px" value="<?php echo $word; ?>"></div>
    <div class="col-md-3">建议8个字以内</div>
</div>
<div class="row">
    <div class="col-md-1">
        <button id="save">保存</button>
    </div>
</div>

<script type="application/javascript" >
    $(function(){

        var uploadFlag = <?php echo $pic; ?>;//标识是否已经上传图片

        //上传按钮逻辑
        $("#file_upload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'选择图片',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'uploadfileQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/weixin/icon/loading_pic',
            'multi':false,
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*.gif',
            'fileSizeLimit':'30KB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                msg = jsonObj.msg;
                url = jsonObj.url;
                succ = jsonObj.success
                if(succ==1){

                    uploadFlag =1//标识已经上传了图片
                    $('#pic').attr("src",url+"?r="+Math.round(Math.random()*10000))
                }
            }
        });


        //保存按钮逻辑
        $("#save").click(function(){

            if(uploadFlag==0){
                alert('请先上传gif图片')
                return false
            }
            var str=$("input#word").val();

            $.ajax({
                type: "POST",
                url: "/weixin/icon/loading_save",
                data: {word:str,pic:uploadFlag},
                dataType: "json",
                success: function(data){
                    alert(data.msg);
                },
                error: function(msg){
                    alert(JSON.stringify(msg));
                }
            });

        });
    });
</script>