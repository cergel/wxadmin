//进入页面后默认选中文本消息隐藏图文消息
$("input[name='content_type']").change(function () {
    switch ($(this).val()) {
        case "1":
            $("#images_text_msg_area").hide();
            break;
        case "2":
            $("#images_text_msg_area").show();
            break;
        default:
            alert("内容类型暂未被定义")
            break;
    }
});
$("input[name='content_type'][value='1']").click();

//处理日期选择器
$('.date-timepicker').datetimepicker({
    format: "YYYY-MM-DD HH:mm"
});
//处理TAG选择器
//获取所有可用标签
var allow_tags = [];
$.each($("input[name='taglist[]']"), function (i, k) {
    allow_tags[i] = k.value;
});

$('#tag_selector').tagit({
    'placeholderText': '不选为全部用户',
    'autocomplete': {delay: 0, minLength: 1},
    'availableTags': allow_tags,
    'singleFieldDelimiter': ','
});


//处理自定义标签checkbox选中时关联tag
$("input[name='taglist[]']").change(function () {
    if (this.checked) {
        $("#tag_selector").tagit("createTag", this.value);
    } else {
        $("#tag_selector").tagit("removeTagByLabel", this.value);
    }
});

$("#tag_selector").tagit({
    afterTagRemoved: function (event, ui) {
        $("input[name='taglist[]'][value='" + ui.tagLabel + "']").attr("checked", false);
    }
});

//图片上传预览
$(document).on("click", ".upload", function () {
    $("#upload_btn").click();
    return false;
});


//处理上传逻辑
$("#upload_btn").change(function () {
    file = this.files[0];
    if (!/image|audio/.test(file.type)) {
        alert("目前仅支持上传图像与音频");
        return false;
    }
    $('#PostForm').ajaxSubmit({
        "url": "/app/assets/upload",
        "type": "POST",
        "dataType": "json",
        "success": function (msg) {
            if (msg.status) {
                $("#img_preview").html('<img class="img-responsive" src="/uploads/Assets/' + msg.url + '" />');
                $("input[name='img_path']").val('/uploads/Assets/' + msg.url);
                $('#PostForm')[0].reset();
            }
        }
    });
});

//保存配置前进行校验
$("#save_btn").click(function () {
    //移除所有的错误状态
    $(".has-error").removeClass("has-error");
    $(".help-block").text("");
    //判断标题是否大于两个字
    title = $("input[name='msg_title']").val();
    if (title.length < 2 || title.length > 20) {
        $("input[name='msg_title']").parents(".control-group").addClass("has-error");
        $("input[name='msg_title']").parents(".control-group").find(".help-block").text("标题在2-10字之间");
        return false;
    }
    //判断图文消息图片链接是否为空
    switch ($("input[name='content_type']:checked").val()) {
        case "2":
            if ($("input[name='img_path']").val() == "") {
                $("input[name='img_path']").parents(".control-group").addClass("has-error");
                $("input[name='img_path']").parents(".control-group").find(".help-block").text("图文消息需要上传封面图")
                return false;
            }
            if ($("input[name='msg_link']").val() == "") {
                $("input[name='msg_link']").parents(".control-group").addClass("has-error");
                $("input[name='msg_link']").parents(".control-group").find(".help-block").text("图文消息需要填写链接")
                return false;
            }
            break;
        default:
    }

    //判断是否输入了消息内容
    if ($("textarea[name='content_text']").val().length < 5 || $("textarea[name='content_text']").val().length > 250) {
        $("textarea[name='content_text']").parents(".control-group").addClass("has-error");
        $("textarea[name='content_text']").parents(".control-group").find(".help-block").text("消息内容最少输入5个字最多不超过250字")
        return false;
    }

    //判断是否勾选了相关渠道如果没有勾选则提示
    if ($("input[name='channel[]']:checked").size() < 1) {
        $(".share_platform").parents(".control-group").addClass("has-error");
        $(".share_platform").parents(".control-group").find(".help-block").text("请勾选推送平台")
        return false;
    }
    //判断是否设置推送时间
    check_time = (Date.parse($("input[name='push_time']").val()) <= (Date.parse(new Date()) + 86400000));
    if ($("input[name='push_time']").val() != "" && check_time) {
        $("input[name='push_time']").parents(".control-group").addClass("has-error");
        $("input[name='push_time']").parents(".control-group").find(".help-block").text("只能预约" + new Date(Date.parse(new Date()) + 86400000).toLocaleString() + "后的推送")
        return false;
    }
    //生成AJAX对象
    var data = {};
    data.msg_title = $("input[name='msg_title']").val();
    data.content_type = $("input[name='content_type']:checked").val();
    data.img_path = $("input[name='img_path']").val();
    data.msg_link = $("input[name='msg_link']").val();
    data.content_text = $("textarea[name='content_text']").val();
    data.push_time = $("input[name='push_time']").val();
    var channel = [];
    $.each($("input[name='channel[]']:checked"), function (i, k) {
        channel[i] = k.value;
    });
    data.channel = channel.join(",");

    var taglist = []
    $.each($("input[name='taglist[]']:checked"), function (i, k) {
        taglist[i] = k.value;
    });
    data.taglist = taglist.join(",");
    //console.log(JSON.stringify(data));
    $.ajax({
        'url': "/message/message/save",
        'data': data,
        'dataType': "json",
        "type": "post",
        "success": function (msg) {
            if (msg.ret == "0") {
                alert("消息添加成功,请等待后台根据标签生成推送用户列表");
                window.location = "/message/message/status";
            } else {
                alert("消息创建失败,详情".JSON.stringify(msg.msg));
            }
        }
    })
    return false;
});
