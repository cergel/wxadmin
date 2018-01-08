
$(function(){
    $("input[name*=push_type]").change(function(){
        if($(this).val() == 0) {
            $("input[name*='task_id']").attr("readonly",false);
        }
        else {
            $("input[name*='task_id']").attr("readonly",true);
            $("#MessageNotice_task_id").val(0);

        }
    })
    
    $("#submit").click(function () {
        //移除所有的错误状态
        $(".has-error").removeClass("has-error");
        $(".help-block").text("");

        /**
         * 验证任务ID是否为空
         */
        if($("input[name*='push_type']:checked").val() == 0) {
            $taskId = $("input[name*='task_id']").val(); 
            if($taskId ==0) {
                alert("任务ID不可为0");
                return false;
            }
            if($taskId.length <=0) {
                $("input[name='task_id']").parents(".control-group").addClass("has-error");
                $("input[name='task_id']").parents(".control-group").find(".help-block").text("任务ID不能为空");
                return false;
            }
        }
        if($("input[name*='push_type']:checked").val() == 2) {
            var $userFile = $("#MessageNotice_user_file").val(),
            $userFile1 = $("#user_file").val();
            if($userFile == '' && $userFile1 == '') {
                alert("请导入文件用户openId");
                return false;
            }
        }

        /**
         * 验证所选平台的跳转链接是否为空
         */
        var $checkChannelVal = '';
        var $bool = true;
        $("[do=checkbox]").each(function(){
            if($(this).is(":checked")){
                $checkChannelVal = $(this).val();
                if ($("#channelUrl" + $checkChannelVal).val() == "") {
                    $attrName = $("#channelUrl" + $checkChannelVal).attr('channelname');
                    alert('请填写' + $attrName);
                    $bool = false;
                }
            }
        })
        if($checkChannelVal == ''){
            alert("请选择推送平台");
            return false;
        }
        if($bool == false) {
            return false;
        }
        
        if($("input[name*='is_push']:checked").val() == 1){
           $msg =  $("input[name*='push_msg']").val();
                   if ($msg == '') {
                alert("请输入push文案");
                return false;
            }
        }
   
    });
})
//保存配置前进行校验

