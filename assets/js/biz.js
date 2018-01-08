/**
 * Created by panyuanxin on 16/8/23.
 */
var json = {};
$(document).on("click", ".upload", function () {
    item = $(this);
    callback_type = $(this).attr("data-type");
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
        "url": "/app/assets/cos",
        "type": "POST",
        "dataType": "json",
        "success": function (msg) {
            if (msg.status) {
                //处理icon图标
                if (callback_type == "cover_icon") {
                    $(item).parents(".controls").find('input[name="picurl"]').val(msg.url);
                    $(item).parents(".controls").find('button[name="button"]').text("更换");
                    $(item).parents(".controls").find('.img_prview').html('<img width="96"  src="' + msg.url + '" />');
                }
                $('#PostForm')[0].reset();
            }
        }
    });
});

$(".tmp_card").hide();
$(".add_recommend").click(function () {

    $(".recommend_card").append($("#recommend_card").clone());
    showPageNum();
    return false;
});

//右上角页数
function showPageNum() {
    $.each($(".recommend_card").find(".pagenum"), function (i, k) {
        $(k).text(i + 1);
    });
}

//因为都是动态添加的所以删除需要进行事件绑定delete_recommend

$(document).on("click", ".delete_recommend", function () {
    if (confirm("确认删除")) {
        $(this).parents('#recommend_card').detach();
        showPageNum();
    }

    return false
});

$("input[name='movieId']").blur(function () {
    get_movieInfo(this.value);
});

//获取影片信息
function get_movieInfo(movieId) {
    $.ajax({
        "url": "http://commoncgi.wepiao.com/channel/movie/get-movie-info",
        "dataType": "script",
        "data": {"movieId": movieId}
    });
}
var MovieData = {};
MovieData.set = function (i, k) {
    $("input[name='movieId']").parent().find(".help-block").empty();
    if ($.isEmptyObject(k.info)) {
        $("input[name='movieId']").val('');
        $("input[name='movieId']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>OM系统中不存在该影片ID!</span>");
        return false;
    } else {
        //判断数据库中是否已经设置相关的影片ID 如果设置则给出提示
        $(".preview").html("<img src='" + k.info.poster_url + "' />")
    }
}

//删除图片按钮
$(".del_picurl").click(function () {
    $(this).parent().find("input[name='picurl']").val("");
    $(this).parent().find(".img_prview").html("");
    return false;
});
var config = {};
$("#config_btn").click(function () {
    //重置所有错误状态
    $(".help-block").html('');
    //获取影片ID
    config.movieId = $("input[name='movieId']").val();
    if (config.MovieId == "") {
        $("input[name='movieId']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>影片ID不能为空</span>");
    }

    config.start = $("input[name='start']").val();
    if (config.start == "") {
        $("input[name='start']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>开始日期不能为空</span>");
    }

    config.end = $("input[name='end']").val();
    if (config.start == "") {
        $("input[name='end']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>结束日期不能为空</span>");
    }

    if (Date.parse(config.end) <= Date.parse(new Date())) {
        $("input[name='end']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>结束日期不能小于当前日期</span>");
    }

    if (Date.parse(config.end) <= Date.parse(config.start)) {
        $("input[name='end']").parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>结束日期不能小于开始日期</span>");
    }
    config.background_img = $(".background_img").find("input[name='picurl']").val();
    if (config.background_img == "") {
        $(".background_img").find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>背景图不能为空</span>");
    }

    config.background_img_blur = $(".background_img_blur").find("input[name='picurl']").val();
    if (config.background_img_blur == "") {
        $(".background_img_blur").find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>高斯模糊背景图不能为空</span>");
    }
    config.guide_pic = $(".guide_pic").find("input[name='picurl']").val();

    if ($(".platform").find("input:checked").size() == 0) {
        $(".platform").find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>至少选择一个投放平台</span>");

    }
    //判断投放平台
    var platform = [];
    $.each($(".platform").find("input:checked"), function (i, k) {
        platform[i] = k.value;
    });
    config.platform = platform;
    //判断推荐位是否有未填写的内容
    $.each($(".recommend_card").find(".recommend_required"), function (i, k) {
        if ($(k).val() == "") {
            $(k).parent().find(".help-block").html("<span style='color: rgba(164, 8, 0, 0.93)'>必填项</span>");
        }
    });

    config.super8_pic = $(".super8_pic").find("input[name='picurl']").val();
    config.super8 = $("input[name='super8']").val();
    console.log(config);
    if (config.super8_pic != "" && config.super8 != "") {
        config.super8 = $("input[name='super8']").val();
        config.super8_pic = $(".super8_pic").find("input[name='picurl']").val();
    } else {
        config.super8 = "";
        config.super8_pic = "";
    }


    //判断推荐
    var recommend = [];
    $.each($(".recommend_card").find("#recommend_card"), function (i, item) {
        recommend_item = {};
        recommend_item.avatar = $(item).find(".avatar >[name='picurl']").val();
        recommend_item.remark = $(item).find(".remark >[name='remark']").val();
        recommend_item.content = $(item).find(".content >[name='content']").val();
        recommend_item.name = $(item).find(".name >[name='name']").val();
        recommend[i] = recommend_item;
    });
    config.recommend = recommend;

    var options = {
        dom: '.config_json'
    };
    $("#save_btn").removeAttr("disabled");
    var check_form = true;
    $.each($(".help-block"), function (i, k) {
        if ($(k).html() != "") {
            check_form = false;
            $("#save_btn").attr("disabled", "disabled");
        }
    });
    if (check_form) {
        var jf = new JsonFormater(options);
        jf.doFormat(JSON.stringify(config));
    } else {
        alert("表单填写不完全,请检查");
    }

    return false;

});

var remoteUrl = "/app/biz/create";
$("#save_btn").click(function () {
    $.ajax({
        "url": remoteUrl,
        "data": config,
        "dataType": "json",
        "method": "POST",
        "success": function (msg) {
            $("#alert").html(msg.msg);
            if (msg.ret == 0) {
                $("#alert").removeClass("alert-danger");
                $("#alert").addClass("alert-success");
                window.location.href = "/app/biz/index";
            } else {
                $("#alert").removeClass("alert-success");
                $("#alert").addClass("alert-danger");
            }
        },
    });
    return false;
});


//编辑
if (typeof(Config) == 'object') {
    console.log(Config);
    remoteUrl = "/app/biz/update/id/" + Config.movieId;
    //编辑时影片ID置灰
    $("input[name='movieId']").val(Config.movieId);
    $("input[name='movieId']").attr("disabled", true);
    $("input[name='start']").val(Config.start);
    $("input[name='end']").val(Config.end);
    if (Config.super8 !== undefined && Config.super8_pic !== undefined) {
        $("input[name='super8']").val(Config.super8);
        $(".super8_pic").find("input[name='picurl']").val(Config.super8_pic);
        $(".super8_pic").find('.img_prview').html('<img width="96"  src="' + Config.background_img + '" />');
        $(".super8_pic").find("button").text('更换');
    }

    $(".background_img").find("input[name='picurl']").val(Config.background_img);
    $(".background_img").find('.img_prview').html('<img width="96"  src="' + Config.background_img + '" />');
    $(".background_img").find("button").text('更换');

    $(".background_img_blur").find("input[name='picurl']").val(Config.background_img_blur);
    $(".background_img_blur").find('.img_prview').html('<img width="96"  src="' + Config.background_img_blur + '" />');
    $(".background_img_blur").find("button").text('更换');
    $(".background_img_blur").find("button").text('更换');

    if (Config.guide_pic != "") {
        $(".guide_pic").find("input[name='picurl']").val(Config.guide_pic);
        $(".guide_pic").find('.img_prview').html('<img width="96"  src="' + Config.guide_pic + '" />');
        $(".guide_pic").find("button[name='button']").text('更换');
    }
    $.each(Config.platform, function (i, k) {
        $(".platform").find("input[value='" + k + "']").attr("checked", true);
    });
    //复制出数量足够多的推荐位
    $.each(Config.recommend, function (i, k) {
        $(".add_recommend").click();
        card = $(".recommend_card").find(".well")[i];
        $(card).find("input[name='remark']").val(k.remark);
        $(card).find("input[name='picurl']").val(k.avatar);
        $(card).find("input[name='name']").val(k.name);
        $(card).find('.img_prview').html('<img width="96"  src="' + k.avatar + '" />');
        $(card).find("textarea[name='content']").val(k.content);
    });

} else {
    //新建表单默认选上CHECKBOX
    $.each($("input[type='checkbox']"), function (i, k) {
        k.checked = true;
    });
}