/**
 * 简单文件上传+图片预览
 * @author CHAIYUE
 * @param setting
 */
var uploadPreview = function (setting) {

    var _self = this;
    _self.IsNull = function (value) {
        if (typeof (value) == "function") {
            return false;
        }
        if (value == undefined || value == null || value == "" || value.length == 0) {
            return true;
        }
        return false;
    }

    _self.DefautlSetting = {
        UpBtn: "",
        DivShow: "",
        ImgShow: "",
        Width: 100,
        Height: 100,
        ImgType: ["gif", "jpeg", "jpg", "bmp", "png"],
        ErrMsg: "选择文件错误,图片类型必须是(gif,jpeg,jpg,bmp,png)中的一种",
        callback: function () {
        },
        Url: '/filmFestival/ajaxUpload'
    };

    _self.Setting = {
        UpBtn: _self.IsNull(setting.UpBtn) ? _self.DefautlSetting.UpBtn : setting.UpBtn,
        DivShow: _self.IsNull(setting.DivShow) ? _self.DefautlSetting.DivShow : setting.DivShow,
        ImgShow: _self.IsNull(setting.ImgShow) ? _self.DefautlSetting.ImgShow : setting.ImgShow,
        Width: _self.IsNull(setting.Width) ? _self.DefautlSetting.Width : setting.Width,
        Height: _self.IsNull(setting.Height) ? _self.DefautlSetting.Height : setting.Height,
        ImgType: _self.IsNull(setting.ImgType) ? _self.DefautlSetting.ImgType : setting.ImgType,
        ErrMsg: _self.IsNull(setting.ErrMsg) ? _self.DefautlSetting.ErrMsg : setting.ErrMsg,
        callback: _self.IsNull(setting.callback) ? _self.DefautlSetting.callback : setting.callback,
        Url: _self.IsNull(setting.Url) ? _self.DefautlSetting.Url : setting.Url
    };

    _self.getObjectURL = function (file) {
        var formData = new FormData();
        formData.append("file", file);
        var url = null;
        $.ajax({
            url: _self.Setting.Url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            async: false,//关闭异步提交
            processData: false,
            contentType: false,
            beforeSend: function () {
                console.log("正在进行，请稍候");
            },
            success: function (responseStr) {
                if (responseStr.code == 0) {
                    url = responseStr.data;
                } else {
                    url = false;
                    alert(responseStr.msg);
                }
            },
            error: function (responseStr) {
                alert("上传失败请重试");
            }
        });
        return url;
        /*  return false;
         if (window.createObjectURL != undefined) {
         url = window.createObjectURL(file);
         } else if (window.URL != undefined) {
         url = window.URL.createObjectURL(file);
         } else if (window.webkitURL != undefined) {
         url = window.webkitURL.createObjectURL(file);
         }
         return url;*/
    }

    _self.Bind = function () {

        document.getElementById(_self.Setting.UpBtn).onchange = function () {
            if (this.value) {
                if (!RegExp("\.(" + _self.Setting.ImgType.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                    alert(_self.Setting.ErrMsg);
                    this.value = "";
                    return false;
                }
                if (navigator.userAgent.indexOf("MSIE") > -1) {
                    try {
                        var ObjectURL =  _self.getObjectURL(this.files[0]);
                        $('#'+_self.Setting.ImgShow).attr('src',ObjectURL);
                        $('#'+_self.Setting.ImgShow).next(":input[type='hidden']").val(ObjectURL);
                    } catch (e) {
                        var div = document.getElementById(_self.Setting.DivShow);
                        this.select();
                        top.parent.document.body.focus();
                        var src = document.selection.createRange().text;
                        document.selection.empty();
                        document.getElementById(_self.Setting.ImgShow).style.display = "none";
                        div.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                        div.style.width = _self.Setting.Width + "px";
                        div.style.height = _self.Setting.Height + "px";
                        div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src;
                    }
                } else {
                    var ObjectURL =  _self.getObjectURL(this.files[0]);
                    $('#' + _self.Setting.ImgShow).attr('src',ObjectURL);
                    $('#' + _self.Setting.ImgShow).next(":input[type='hidden']").val(ObjectURL);
                }
                _self.Setting.callback();
            }
        }
    }
    _self.Bind();
}
/*
 function file_click() {
 var WARP = document.getElementById('warp');
 var WARP_LI = WARP.getElementsByTagName('li');
 for (var i = 0; i < WARP_LI.length; i++) {
 new uploadPreview({UpBtn: "up_img_WU_FILE_" + i, ImgShow: "imgShow_WU_FILE_" + i});
 }
 }
 window.onload = file_click;
 */