var item = null;
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
        "url": "/app/assets/upload",
        "type": "POST",
        "dataType": "json",
        "success": function (msg) {
            if (msg.status) {
                //处理添加日签页面
                if (callback_type == "add_page") {
                    $(item).parents(".page_item").find('.pic_path').val("/uploads/Assets/" + msg.url);
                    $(item).parents(".page_item").find('button').text("换图");
                    $(item).parents(".page_item").find('.preview').html('<div class="buy_button_area" style="position:absolute; z-index:2; right:10px; top:100px">' +
                        '</div><div class="general_btn_area" style="position:absolute; z-index:2; left:10px; top:100px">' +
                        '</div><img style="width:240px;height:320px;display: inline;" src="/uploads/Assets/' +
                        msg.url + '">');
                }
                //处理添加背景音乐
                if (callback_type == "background_music") {
                    $(item).parents(".controls").find('input').val("/uploads/Assets/" + msg.url);
                    $(item).parents(".controls").find('button').text("更换");
                    $(item).parents(".controls").find('#audio_player').html('<audio autoplay="autoplay" loop="loop" controls="controls" src="/uploads/Assets/' + msg.url + '"></audio>');
                }
                //处理icon图标
                if (callback_type == "cover_icon") {
                    $(item).parents(".controls").find('input').val("/uploads/Assets/" + msg.url);
                    $(item).parents(".controls").find('button').text("更换");
                    $(item).parents(".controls").find('.img_prview').html('<img width="64" height="64" src="/uploads/Assets/' + msg.url + '" />');
                }

                //处理分享图标
                if (callback_type == "share_icon") {
                    $(item).parents(".controls").find('input').val("/uploads/Assets/" + msg.url);
                    $(item).parents(".controls").find('button').text("更换");
                    $(item).parents(".controls").find('.img_prview').html('<img width="64" height="64" src="/uploads/Assets/' + msg.url + '" />');
                }
                $('#PostForm')[0].reset();
            }
        }
    });
});

//新增页面上传组件
$('.addpage').click(function () {
    addPage();
    return false;
});

function addPage() {
    $(".pages").append('<div class="control-group page_item well">' +
        '<label class="control-label pageNo">页数</label>' +
        '<div class="controls center-block">' +
        '<button class="upload" data-type="add_page">上传</button>' +
        '<input class="pic_path"  type="hidden" />' +
        '<div class="preview" style = "position:relative;"></div>' +
        '<div class="mailto"></div>' +
        '<i class="remove_page menu-icon glyphicon glyphicon-icon glyphicon-remove" style="cursor: pointer"></i>' +
        '</div>' +
        '</div>');
    changePageEvent();
}

function changePageEvent() {
    //重新绘制前端状态
    totalPage = $(".page_item").size();
    $(".pages_number").empty();
    $.each($(".page_item"), function (k, i) {
        if (k == 0) {
            $(i).find(".pageNo").text('封面');
        } else {
            $(i).find(".pageNo").text('第' + k + '页');
        }
        if (k != 0 && k == (totalPage - 1)) {
            $(i).find(".pageNo").text('封底');
        }

        //判断当前页码有没有包含class=addmailto的button
        var bool = $(".mailto").eq(k).children().hasClass("addmailto");
        var bool2 = $(".mailto").eq(k).children().hasClass("update_btn");

        if (k == totalPage - 2 && k!=0 && !bool && !bool2) {
            $(".mailto").eq(k).append('<input type="button"  onclick="popDiv(' + k + ')" class="addmailto" value="添加锚点"/>');
        }
        if ((k==0 || k == (totalPage-1) && (bool || bool2))){
            $(".mailto").eq(k).empty();
        }

        $(".pages_number").append('<label class="checkbox inline">' +
            '<input type="checkbox" value="' + k + '" checked="checked" >' +
            $(i).find(".pageNo").text() +
            '</label>');
    });
}

//删除按钮
$(document).on("click", ".remove_page", function () {
    $(this).parents('.page_item').detach();
    changePageEvent();
    return false
});

//处理通用按钮关闭打开逻辑
$(document).on("change", "input[name='general_btn']", function () {
    if ($(this).val() != '0') {
        $(".general_btn_input_area").show();
    } else {
        $(".general_btn_input_area").hide();
        $(".general_btn_input_area").find('input').val('');
        $(".general_btn_input_area").find(':checkbox').removeAttr('checked');
        $(".general_btn_area").empty();
        //表单清空
    }
    return false
});
//处理购票按钮关闭打开逻辑
$(document).on("change", "input[name='buy_btn_radio']", function () {
    if ($(this).val() != '0') {
        $(".buy_btn_input").show();
    } else {
        $(".buy_btn_input").hide();
        $(".buy_btn_input").find('input').val('');
        $(".buy_btn_input").find(':checkbox').removeAttr('checked');
        $(".buy_button_area").empty();
        //表单清空
    }
    return false
});
//处理通用按钮选择预览逻辑
$(document).on("change", ":checkbox", function () {
    //获取当前是哪个Checkbox发生变化
    btn_type = $(this).parents(".pages_number").attr("data-type");
    //获取当前checkbox的值如果和是否被勾选的状态
    page = $(this).val();
    is_checked = $(this)[0].checked;


    if (btn_type == 'buy_btn') {
        if (is_checked) {
            $($(".page_item")[page]).find(".buy_button_area").html("<button class='pre_btn'>购票按钮</button>")
        } else {
            $($(".page_item")[page]).find(".buy_button_area").empty();
        }
    }

    if (btn_type == 'general_btn') {
        if (is_checked) {
            $($(".page_item")[page]).find(".general_btn_area").html("<button class='pre_btn'>通用按钮</button>")
        } else {
            $($(".page_item")[page]).find(".general_btn_area").empty();
        }
    }
    return false
});
//预览按钮禁止点击
$(document).on("click", ".pre_btn", function () {
    return false
});

//生成配置文件
$("#config_btn").click(function () {
    makeConfig();
    return false;
});

function makeConfig() {
    var config = {};
    //判断页数是否大于三页
    if ($(".page_item").size() < 3) {
        alert("请创建三页以上再点击保存配置");
        return false;
    }

    //检查是否开启购票按钮如果开启购票按钮的话渠道号必填
    check_buy_flag = $('input[name="buy_btn_radio"]:checked').val();
    buy_channelId = $('input[name="buy_channelId"]').val();
    if (check_buy_flag == "1") {
        if (buy_channelId == "") {
            alert("输入一下购票渠道ID吧");
            return false;
        }
    }

    //检查封面图是否设置
    $.each($(".required"), function (i, k) {
        if ($(k).val() == "") {
            alert("请完整填写后再提交" + $(k).attr("name"));

            return false;
        }
    });


    //检查必填项目是否设置
    var page = [];
    var indexs = [];
    var is_index = 0;
    var j = 0;
    //强制最后一页可购票
    var page_total_num = $(".page_item").size();
    $.each($(".page_item"), function (i, k) {

        if (i == (page_total_num - 1)) {
            $(buy_check_boxs)[i].checked = true;
        }

        page[i] = {};

        var img_url = $(k).find("input[type='hidden']").val();
        if (img_url == "") {
            alert($(k).find(".pageNo").text() + " 图片设置失败,请重新设置");
            return false;
        }
        page[i].url = "#CDNPATH#" + img_url;
        //判断是否需要显示购票按钮
        check_buy_flag = $('input[name="buy_btn_radio"]:checked').val();
        if (check_buy_flag == "1") {
            //检查对应的页面是否被选中
            buy_check_boxs = $('#buy_checkboxs').find("input");
            if ($(buy_check_boxs)[i].checked) {
                page[i].buy_flag = 1;
                page[i].buy_channelId = $('input[name="buy_channelId"]').val();
                page[i].buy_url = "#BUY_TICKET_URL#";
            } else {
                page[i].buy_flag = "0";
                page[i].buy_url = ""
            }
        } else {
            page[i].buy_flag = "0";
            page[i].buy_url = ""
        }
        //判断是否需要显示通用按钮
        check_btn_flag = $('input[name="general_btn"]:checked').val();
        if (check_btn_flag != "0") {
            //检查对应的页面是否被选中
            general_checkboxs = $('#general_checkboxs').find("input");
            if ($(general_checkboxs)[i].checked) {
                page[i].btn_type = check_btn_flag;
                page[i].btn_flag = "1";
                page[i].app_url = $("input[name='app_link']").val();
                page[i].wx_url = $("input[name='wx_link']").val();
                page[i].mqq_url = $("input[name='mqq_link']").val();
            } else {
                page[i].btn_type = check_btn_flag;
                page[i].btn_flag = "0";
                page[i].app_url = "";
                page[i].wx_url = "";
                page[i].mqq_url = "";
            }
        } else {
            page[i].btn_flag = "0";
            page[i].btn_type = "0";
            page[i].app_url = "";
            page[i].wx_url = "";
            page[i].mqq_url = "";
        }
        var m_val = $(k).find("input[name='m_val']").val();
        if (m_val) {
            is_index = 1;
            indexs[j]={};
            indexs[j].introduce = i+1;
            indexs[j].introduce_name = m_val;
            j++;
        }
    });
    //合成其他信息
    var json = {};
    json.movieId = $('input[name="movieId"]').val();
    json.is_index = is_index;
    json.backgroundMusic = $('input[name="background_music"]').val();
    if (json.backgroundMusic != "") {
        json.backgroundMusic = "#CDNPATH#" + $('input[name="background_music"]').val();
    }


    json.baseCount = $('input[name="baseCount"]').val();
    json.buy_button = $('input[name="buy_btn_radio"]:checked').val();
    json.buy_channelId = $('input[name="buy_channelId"]').val();

    json.general_btn = $('input[name="general_btn"]:checked').val();
    json.general_link_wx = $("input[name='wx_link']").val();
    json.general_link_mqq = $("input[name='mqq_link']").val();
    json.general_link_app = $("input[name='app_link']").val();

    json.share_img = "#CDNPATH#" + $('input[name="share_img"]').val();
    json.cover_img = "#CDNPATH#" + $('input[name="cover_img"]').val();
    json.cover_img_large = "#CDNPATH#" + $('input[name="cover_img_large"]').val();
    json.basePvCount = $('input[name="basePvCount"]').val();
    json.baseGetCount = $('input[name="baseGetCount"]').val();
    json.shareTitle = $('input[name="shareTitle"]').val();
    json.shareDesc = $('input[name="shareDesc"]').val();
    json.shareLink = "http://wx.wepiao.com/movie_guide.html?movieId=" + json.movieId + "&cityId=10";

    json.title = $('input[name="title"]').val();
    json.subtitle = $('input[name="subtitle"]').val();
    var sharePlatform = [];
    var count = 0;
    $.each(($('.share_platform').find(":checkbox")), function (i, k) {
        if (k.checked) {
            sharePlatform[count] = k.value;
            count++;
        }
    });

    json.shareVia = sharePlatform;
    json.page = page;
    json.indexs = indexs;

    var options = {
        dom: '.config_json'
    };
    var jf = new JsonFormater(options);
    jf.doFormat(JSON.stringify(json));
    $("#save_btn").removeAttr("disabled");
    return false;
}

$("#save_btn").click(function () {

    $.ajax({
        "url": "/app/movieGuide/create",
        "dataType": 'json',
        "type": "post",
        "data": JSON.parse($(".config_json").text()),
        "success": function (msg) {
            if (msg.ret == 0) {
                alert(msg.msg);
                window.location.href = "/app/movieGuide/";
            } else {
                alert(msg.msg);
            }
        }
    })
});

//编辑
if (typeof(guideConfig) == 'object') {
    //填充基本信息
    $('input[name="movieId"]').val(guideConfig.movieId);
    $('input[name="shareTitle"]').val(guideConfig.shareTitle);
    $('input[name="shareDesc"]').val(guideConfig.shareDesc);
    $('input[name="baseCount"]').val(guideConfig.baseCount);
    $('input[name="share_img"]').val(guideConfig.share_img);
    $('input[name="cover_img"]').val(guideConfig.cover_img);
    $('input[name="cover_img_large"]').val(guideConfig.cover_img_large);
    $('input[name="basePvCount"]').val(guideConfig.basePvCount);
    $('input[name="baseGetCount"]').val(guideConfig.baseGetCount);
    $('input[name="title"]').val(guideConfig.title);
    $('input[name="subtitle"]').val(guideConfig.subtitle);

    //判断购票按钮是否被点击

    if (guideConfig.buy_button != "0") {
        //默认选中按钮
        $('input[name="buy_channelId"]').val(guideConfig.buy_channelId);
        $("input[name='buy_btn_radio'][value='1']").attr("checked", 'checked');
        $(".buy_btn_input").show();
    } else {
        $("input[name='buy_btn_radio'][value='0']").attr("checked", 'checked');
        $('input[name="buy_channelId"]').val('');
        $(".buy_btn_input").hide();
    }
    //通用按钮处理
    if (guideConfig.general_btn != "0") {
        $('input[name="wx_link"]').val(guideConfig.general_link_wx);
        $('input[name="mqq_link"]').val(guideConfig.general_link_mqq);
        $('input[name="app_link"]').val(guideConfig.general_link_app);
        $("input[name='general_btn'][value='" + guideConfig.general_btn + "']").attr("checked", 'checked');
        $(".general_btn_input_area").show();
    } else {
        $("input[name='general_btn'][value='0']").attr("checked", 'checked');

        $(".general_btn_input_area").hide();
    }
    //填充背景音频
    if (guideConfig.backgroundMusic != "") {
        $('#audio_player').html('<audio autoplay="autoplay" loop="loop" controls="controls" src="' + guideConfig.backgroundMusic + '"></audio>');
        $('#audio_player').parent('.controls').find('input[name="background_music"]').val(guideConfig.backgroundMusic)
    }

    $("input[name='cover_img']").parent(".controls").find(".img_prview").html('<img width="64" height="64" src="' + guideConfig.cover_img + '" />');
    $("input[name='cover_img_large']").parent(".controls").find(".img_prview").html('<img width="64" height="64" src="' + guideConfig.cover_img_large + '" />');
    $("input[name='share_img']").parent(".controls").find(".img_prview").html('<img width="64" height="64" src="' + guideConfig.share_img + '" />');
    //填充分享平台
    $.each($(".share_platform").find('input'), function (i, k) {
        if ($.inArray(k.value, guideConfig.shareVia) != -1) {

            k.checked = true;
        }
    });

    //渲染页面由 不包含checkbox
    $.each(guideConfig.page, function (i, k) {
        page_size = $(guideConfig.page).size();
        if (i == 0) {
            page_label = "封面";
        }
        else if (i == page_size - 1) {
            page_label = "封底";
        } else {
            page_label = "第" + i + "页";
        }

        $(".pages").append('<div class="control-group page_item well">' +
            '<label class="control-label pageNo">' + page_label + '</label>' +
            '<div class="controls center-block">' +
            '<button class="upload" data-type="add_page">换图</button>' +
            '<input class="pic_path"  type="hidden" value="' + k.url + '" />' +
            '<div class="preview" style = "position:relative;">' +
            '<div class="buy_button_area" style="position:absolute; z-index:2; right:10px; top:100px">' +
            '</div><div class="general_btn_area" style="position:absolute; z-index:2; left:10px; top:100px">' +
            '</div><img style="width:240px;height:320px;display: inline;" src="' + k.url + '">'
            +
            '</div>' +
            '<div class="mailto">' +
            '</div>' +
            '<i class="remove_page menu-icon glyphicon glyphicon-icon glyphicon-remove" style="cursor: pointer"></i>' +
            '</div>' +
            '</div>');

        //利用页面改变事件渲染checkbox
        changePageEvent();

    });

    //渲染目录索引
    if (guideConfig.indexs) {
        $.each(guideConfig.indexs, function (i, k) {
            var j = k.introduce -1,
                name = k.introduce_name;
            $(".mailto").eq(j).empty();
            $(".mailto").eq(j).append('<input type="button" class="update_btn" value="修改锚点"/><input type="button" class="del_btn" value="删除锚点"/><label class="m_show">'+ name +'</label><input type="hidden" class="m_val" name="m_val" id="m_val" value="'+ name +'"/>');

        })
    }


    //单独渲染checkbox
    $.each(guideConfig.page, function (i, k) {
        if (k.buy_flag != "1") {
            $('#buy_checkboxs').find('input')[i].checked = false;
        }

        if (k.btn_flag != "0") {
            $('#general_checkboxs').find('input')[i].checked = true;
        }
    });

}

//显示添加锚点层
function popDiv(i) {
    $("#add_mail_div").css('display','block');
    $("#m_num").val(i);
}
//关闭添加锚点层
function closeDiv() {
    $("#add_mail_div").css('display','none');
}
//保存锚点
function saveDiv() {
    var mval = $("#m_title").val(),
        i = $("#m_num").val();
    if(mval.length > 15) {
        alert('不超过15个字符');
        return false;
    }
    if(mval.length = 0) {
        alert('不可为空');
        return false;
    }
    $(".mailto").eq(i).empty();
    $(".mailto").eq(i).append('<input type="button" class="update_btn" value="修改锚点"/><input type="button" class="del_btn" value="删除锚点"/><label class="m_show">'+mval+'</label><input type="hidden" class="m_val" name="m_val" id="m_val" value="'+mval+'"/>');
    $("#add_mail_div").css('display','none');
    $("#m_title").val('');

}

$(document).on("click", ".update_btn", function () {
    var m_val = $(this).parent().find("input[name='m_val']").val(),
        i =  $(this).closest('.page_item').index();
    $("#m_title").val(m_val);
    $("#m_num").val(i);
    $("#add_mail_div").css('display','block');

});
$(document).on("click", ".del_btn", function () {
    var msg = "确定删除该锚点?";
    var i =  $(this).closest('.page_item').index();
    if (confirm(msg)==true){
        $(".mailto").eq(i).empty();
        $(".mailto").eq(i).append('<input type="button"  onclick="popDiv(' + i + ')" class="addmailto" value="添加锚点"/>');
        return true;
    }else{
        return false;
    }

});

