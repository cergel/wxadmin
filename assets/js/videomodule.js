//初始化隐藏预告片列表以及视频预览区域
$(".panel_info").hide();
$(".QQVideo").hide();

$(".search").click(function(){
	//获取影片的ID
	var MovieId = $(this).parents(".item").find(".MovieId").val();
	var Panel = $(this).parents(".item").find(".panel_info")
	if(MovieId==""){
		alert("请输入影片ID");
		return false;
	}
	$.ajax({
		"url":"/app/videomodule/movie",
		"data":{"movieId":MovieId},
		"type":"GET",
		"dataType":"json",
		"success":function(msg){
			if(getJsonObjLength(msg.data) == 0){
				alert("影片信息获取失败");
				return false;
			}
			//展示Panel
			Panel.show();
			//填充基本信息
			$.each(msg.data,function(k,v){
				Panel.find("li[attr="+k+"]").find("span").text(v);
			});
			//填充图片信息
			Panel.find(".MovieImg > img").attr("src",msg.data.poster_url);
			
			//先清空预告片列表然后填充预告片数据
			Panel.find('table').fadeIn(500);
			Panel.find("table").empty();
			Panel.find("table").append('<tr class="tablehead"><th>预告片</th><th ">描述</th><th width="30%">操作</th></tr>');
			if(getJsonObjLength(msg.data.videos) == 0){
				Panel.find("table").append('<tr><td colspan="3">预告片</td></tr>');
			}else{
				$.each(msg.data.videos,function(k,v){
					Panel.find("table").append('<tr><td class="vid">'+v.vid+'</td><td clas="vt">'+v.tt+'</td><td><button class="btn btn-info preview">预览</button> <button class="btn btn-success use">使用</button></td></tr>');
				});
			}
			
		}
		
		
	});
	return false;
});

//给动态加入的DOM元素添加点击侦听事件
$(document).on("click",".use",function(){
	$(this).parents('.item').find('.QQVideo').hide();
	$(this).parents('.item').find('.MovieInfo').show();
	$(this).parents('.item').find('.videoId').val($(this).parents('tr').find('td')[0].innerHTML);
	$(this).parents('.item').find('.videoTitle').val($(this).parents('tr').find('td')[1].innerHTML);
	$(this).parents('.item').find('table').fadeOut(500);
	return false
});
$(document).on("click",".preview",function(){
	$(this).parents('.item').find('.QQVideo').show();
	$(this).parents('.item').find('.MovieInfo').hide();
	$(this).parents('.item').find('.QQVideo').html('<embed src="http://static.video.qq.com/TPout.swf?auto=1&vid='+$(this).parents('tr').find('td')[0].innerHTML +'" quality="high"  align="middle" width="480" height="320" allowScriptAccess="sameDomain" allowFullscreen="true" type="application/x-shockwave-flash"></embed>');
	return false
});  

//JS获取对象长度
function getJsonObjLength(jsonObj) {
        var Length = 0;
        for (var item in jsonObj) {
            Length++;
        }
        return Length;
}

//提交表单失败时，获取已经输入的资料，自动弹出预告片备选列表[用于更新或者第一次提交失败]
if(Video2_Movie != 0){
	$("#item2").find(".search").click();
}
if(Video1_Movie != 0){
	$("#item1").find(".search").click();
}