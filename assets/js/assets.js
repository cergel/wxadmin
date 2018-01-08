$("input").change(function(){
	file = this.files[0];
	if(!/image|css|audio|javascript|html/.test(file.type)){ 
		if(!confirm("虽然可以传，但是墙裂不建议往里面上传非图片要继续嘛?")){
			return false
		} 
	}
	//由于bymax后台会记录POST日志所以不采用base64 POST上传方式而采用传统ajax表单提交方式
	
	$("#_fileForm").ajaxSubmit({
		"url":"upload",
		"type":"POST",
		"dataType":"json",
		"success":function(msg){
			alert(msg.text);
			if(msg.status){
				window.location.reload();
			}
		}
	});

});

$(".rename").click(function(){
	var filename = $(this).parents('tr').find('.filename').text();
	$(this).parents('td').html("<input value='"+filename+"'/><button class='btn_rename' for='"+filename+"'>确定</button>");
});

$(document).on("click",".btn_rename",function(){
	filename = $(this).attr('for');
	newname = $(this).parents('td').find('input').val();
	if(newname == ""){
		alert("新文件名称不能为空");
		return false;
	}
	$.ajax({
		"url":"rename",
		"data":{"filename":filename,"newname":newname},
		"type":"POST",
		"dataType":"json",
		"cache":false,
		"success":function(msg){
			alert(msg.text);
			if(msg.status){
				window.location.reload();
			}
		},
	})
	return false
});

$(document).on("click",".delete",function(){
	var filename = $(this).parents('tr').find('.filename').text();
	if(!confirm('是否删除文件'+filename+'!')){
		return false;
	}
	
	$.ajax({
		"url":"delete",
		"data":{"filename":filename},
		"type":"POST",
		"dataType":"json",
		"cache":false,
		"success":function(msg){
			alert(msg.text);
			if(msg.status){
				window.location.reload();
			}
		},
	})
	return false
});
