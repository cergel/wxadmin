/**
 * Created by panyuanxin on 2016/11/21.
 */
//alert("DaySign_iMusic");
$("#upload_audio").click(function () {
    fileupload.click();
    return false;
});

$("#fileupload").change(function () {
    file = this.files[0];
    if (!/audio/.test(file.type)) {
        if (!confirm("请选择音频文件")) {
            return false
        }
    }

    $("#_fileForm").ajaxSubmit({
        "url": "/app/Assets/upload",
        "type": "POST",
        "dataType": "json",
        "success": function (msg) {
            if (msg.status) {
                $("#DaySign_iMusic").val("/uploads/Assets/" + msg.url);
                $("#audio_player").html('<audio autoplay="autoplay" loop="loop" controls="controls" src="/uploads/Assets/' + msg.url + '"></audio>')
            }
        }
    });

});