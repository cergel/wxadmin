var item = null;
$(".uploadImg").click(function(){
	item = $(this);
	//获取当前的input
	$("#upload_btn").click();
});

//处理图片上传
$("#upload_btn").change(function(){
	file = this.files[0];	
	if(!/image|audio/.test(file.type)){ 
		alert("目前仅支持上传图像与音频"); 
		return false; 
	}
    $('#PostForm').ajaxSubmit({
    "url":"/app/assets/upload",
    "type":"POST",
    "dataType":"json",
    "success":function(msg){
        if(msg.status){
            $(item).parents(".uploadArea").find("img").attr("src","/uploads/Assets/" + msg.url);
            $(item).parents(".uploadArea").find(".ImgPath").val(msg.url);
            $('#PostForm').find("input[type='reset']").click();
            $(item).parents(".uploadArea").find(".delImg").show();
            $(item).parents(".uploadArea").find(".uploadImg").hide();
        }
    }
    });
});

$('.delImg').click(function(){
	$('.delImg').parents(".uploadArea").find("img").attr("src",'');
	$('.delImg').parents(".uploadArea").find(".ImgPath").val('');
	$('.delImg').parents(".uploadArea").find(".delImg").hide();
	$('.delImg').parents(".uploadArea").find(".uploadImg").show();
});




