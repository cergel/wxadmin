<?php
$this->breadcrumbs = array(
    '评论' => array('index'),
    '添加明星评论',
);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
?>
<script type="text/javascript">

    $(function () {
        //最终提交
        $("#addStartComment").click(function () {
            var content = $("#content").val();
            var OpenID = $("#OpenID").val();
            var channel = $("#channel option:selected").val()
            var movieId = $("#movieId").val();

            $.ajax({
                url: "/AddStartComment/addComment",
                data: {"content": content, "OpenID": OpenID, "channel": channel, "movieId": movieId},
                dataType: 'json',
                type: "GET",
                success: function (data) {
                    if (data.succ == 0) {
                        alert(data.msg);
                    } else {
                        alert("添加成功");
                        $("#content").val("");
                        $("#movieId").val("");
                        $("#checkName").val("");
                        $("#OpenID").val("");
                        $("#startName").val("");
                    }
                },
                error: function () {
                    alert("网络异常，请重试");
                }
            })
        });
        //通过明星名字匹配OpenID
        $("#checkName").click(function () {
            var name = $("#startName").val();
            if (name == '') {
                alert("请填写明星姓名");
                return false;
            }
            var openId = $("#OpenID").val();
            if (openId == '') {
                alert("请填写OpenId");
                return false;
            }
            $.ajax({
                url: "/AddStartComment/checkName",
                data: {"name": name,"openId":openId},
                dataType: 'json',
                type: "GET",
                success: function (data) {
                    if (data.num == 0) {
                        alert("明星名字与OpenId不符");
                        return false;
                    } else {
                        alert("检测成功")
                    }
                },
                error: function () {
                    alert("网络异常，请重试");
                }
            })
        })

    })

</script>

<div class="panel-body">
    <div class="form-group">
        <label class="col-sm-2 right-align">评论内容:</label>

        <div class="col-sm-10">
            <textarea id="content" rows="5" cols="50"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 right-align">影片ID:</label>

        <div class="col-sm-10">
            <input id="movieId" type="text"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 right-align">OpenID:</label>

        <div class="col-sm-10">
            <input id="OpenID" type="text"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 right-align">来源:</label>

        <div class="col-sm-10">
            <select id="channel">
                <option value="8">IOS</option>
                <option value="9">安卓</option>
                <option value="3">微信电影票</option>
                <option value="28">手q</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 right-align">明星:</label>

        <div class="col-sm-10">
            <input id="startName" type="text"/>
            <button id="checkName" class="btn btn-default" type="button" style="padding-bottom: 2px;padding-top: 2px;">
                检查
            </button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 right-align"> </label>
        <span class="col-sm-2">
            <button id="addStartComment" class="btn btn-default" type="button"
                    style="padding-bottom: 2px;padding-top: 2px;">添加
            </button>
        </span>
    </div>

</div>
