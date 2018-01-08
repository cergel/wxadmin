var item = null;
$(".field").focus(function(){
	item = $(this).attr("id");
	//获取当前的input的ID方便后续填充数据
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
			alert(msg.text);
			if(msg.status){
				$('#'+item).val(msg.url);
			}
		}
	});
	
	
});

//检查表单必填字段是否为空
$("#config").click(function(){
	$(".form-group").removeClass("has-error");
	
	//检查音频与文案是否全都填满
	
	//生成文案音频对象
	var SelectObj = [];
	$.each($(".selected_Audio"),function(i,k){
		if(k.value != "" && $(".selected_text")[i].value != "")
		{
			obj = {};
			obj.audio = "#CDNPath#"+k.value;
			obj.desc = $(".selected_text")[i].value;
			SelectObj[i] = obj;
		}
	});
	
	//生成选座图标
	var icon = [];
	$.each($(".icon"),function(i,k){
		if(k.value != ""){
			icon[i] = "#CDNPath#" + k.value;
		}
	});
	//生成GIF图
	var gif = [];
	$.each($(".gif"),function(i,k){
		if(k.value != ""){
			gif[i] = "#CDNPath#"+ k.value;
		}
	});
	
	//获取已选座位
	var selected="";
	if($("#Selected").val()!="") selected = "#CDNPath#" + $("#Selected").val();
	var Customization = {};
	Customization.SelectObj = SelectObj;
	Customization.icon = icon;
	Customization.gif = gif;
	Customization.selected = selected;
	
	var options = {
        dom : '#ConfigField'
    };
    var jf = new JsonFormater(options); 
	jf.doFormat(JSON.stringify(Customization));
	//隐藏本按钮显示提交按钮
	$(this).hide();
	$("#submit").show();
	$("#update_btn").show();
	return false;
});

//新建页面提交AJAX请求
$("#submit").click(function(){
	var Post = {}
	Post.MovieId = $("#MovieId").val();
	Post.Start = $("#Start").val();
	Post.End = $("#End").val();
	Post.Config = $("#ConfigField").text();
	//判断是否可以提交
	if(Post.MovieId == "" || Post.Config == ""){
		alert("影片ID与配置文件不能为空");
		$("#config").show();
		$("#submit").hide();
		return false;
	}
	var data = {};
	data.Post  = Post;
	$.ajax({
		"url":"/customization/create",
		"data":data,
		"type":"post",
		'dataType':'json',
		"success":function(msg){
			if(msg.status){
				window.location = "/customization";
			}else{
				alert(msg.msg);
			}
		}
		
	});
});

//更新选坐AJAX提交
$("#update_btn").click(function(){
	var Post = {}
	Post.MovieId = $("#MovieId").val();
	Post.Start = $("#Start").val();
	Post.End = $("#End").val();
	Post.Config = $("#ConfigField").text();
	//判断是否可以提交
	if(Post.MovieId == "" || Post.Config == ""){
		alert("影片ID与配置文件不能为空");
		$("#config").show();
		$("#update_btn").hide();
		return false;
	}
	var data = {};
	data.Post  = Post;
	$.ajax({
		"url":"/customization/update/" + SeatId,
		"data":data,
		"type":"post",
		'dataType':'json',
		"success":function(msg){
			if(msg.status){
				window.location = "/customization";
			}else{
				alert(msg.msg);
			}
		}
		
	});
});

//首页载入列表自动加载影片资料
$(".CustomizationSeatItem").each(function(){
	var item = this;
	$.ajax({
			'url':'/app/videomodule/searchmoive',
			'dataType':'json',
			'data':{'MoiveId':this.id},
			'type':'get',
			'success':function(msg){
				if(msg.ret != 0){
					alert('检索电影失败');
					return false;
				}
				$(item).find(".img").attr("src", msg.data.poster_url);
				$(item).find("h3").text(msg.data.name);
				
			}
		});
			
});

//点击删除按钮删除选坐方案
$(".btn-delete").click(function(){
	var id = $(this).parents(".CustomizationSeatItem").attr("data-id");
	if(confirm("确定要删除本方案?")){
		$.ajax({
			'url':'/customization/delete',
			'dataType':'json',
			'data':{'Post[SeatId]':id},
			'type':'post',
			'success':function(msg){
				window.location.reload();
			}
		});
	}
});

//编辑页面自动格式化json的div
$(document).ready(function(){
	//判断是否为编辑状态
	if(typeof(Config) == "undefined"){
		return false;
	}
	//将配置文件回填至表单之中
	$(Config.SelectObj).each(function(i,k){
		$(".selected_Audio")[i].value = k.audio;
		$(".selected_text")[i].value = k.desc;
	});
	$(Config.icon).each(function(i,k){
		$(".icon")[i].value = k;
	});
	$(Config.gif).each(function(i,k){
		$(".gif")[i].value = k;
	});
	$("#Selected").val(Config.selected);
	//格式化默认的配置文件
	var options = {
        dom : '#ConfigField'
    };
    var jf = new JsonFormater(options); 
	jf.doFormat($("#ConfigField").text());
	
});

//日期时间选择器
 $('.date-timepicker').datetimepicker({
        format:"YYYY-MM-DD HH:mm:ss"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });