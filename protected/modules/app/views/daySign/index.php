<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:56
 */

Yii::app()->getClientScript()->registerCssFile("/css/app/hDate.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/hDate.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-date.js");
?>

<style type="text/css">
    html, body {width:100%;height:100%;padding:0;margin:0; }
    .calendar{width:500px;}
    <?php
        //弄样式
        if(!empty($list)){
        foreach($list as $info){
            echo ".day_".$info['iID']."{color:red;}";
        }
        }
    ?>
</style>
<div style="width:540px;margin:40px auto 0 auto;">
    <input id="Text2" type="text" style="width:500px;visibility:hidden;"/>
    <a href="/app/daySign/create">添加</a>
    <div id="detail" style="width:500px;padding-top:250px;">

    </div>
</div>

<script>
    $(function(){
        calendar.show({
            id: Text2,ok: function (e) {
                $.post('/app/daySign/getDay',{iID:e},function(ret){
                    $("#detail").empty();


                    var data=JSON.parse(ret);
                    if(data.empty == 1){

                        window.location.href="/app/daySign/create/iID/"+e;
                        return;
                    }

                    detail ="<center><p style=>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/app/daySign/update/id/"+e+"'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='JavaScript:;' onclick=delcfm("+e+")>删除</a><p></center>";
                    detail += "<img src="+data.data.iBackground+">";

                    $('#detail').append(detail);
                });
            }
        });
    });


    function delcfm(id) {
        if (confirm("确认要删除？")) {
            window.location.href="/app/daySign/delete/iID/"+id;
        }else{
            return false;
        }
    }

</script>