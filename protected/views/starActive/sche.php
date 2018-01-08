<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/4
 * Time: 16:19
 */
$this->breadcrumbs=array(
    '明星见面会'=>array('index'),
    '活动详情',
    '搜索排期',
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
//Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);


Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
    format:\"YYYY-MM-DD\"
    }).next().on(ace.click_event, function(){
    $(this).prev().focus();
    });

");
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'star-active-create-sche-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
));
?>
<div class="page-header">
    <h1>搜索排期</h1>
</div>
<div class="row">
    <div class="modal-body">
        <div>
            <table>
                <tr>
                    <td><label for="key_cinema">影院ID：</label></td>
                    <td><input type="text" id="key_cinema" placeholder="填写影院关键字"size="20"/></td>
                    <td><label for="key_cinema">影片ID：</label></td>
                    <td><input type="text" id="key_film" placeholder="填写影片关键字"size="20"/><br></td>
                    <td><label for="key_cinema">时间：</label></td>
                    <td><input type="text" id="key_date" placeholder="" class="date-timepicker" style="width: 100px"size="20"/></td>
                    <td>&nbsp; &nbsp; &nbsp;</td>
                    <td><button id="search_sche_button" type="button" class="btn btn-primary">查询</button><br></td>
                </tr>
            </table>
        </div>
    </div>
    <input type="hidden" id="a_id" name="a_id" value="<?php echo $aid; ?>">
    <input type="hidden" id="filmName" name="filmName" value="">
    <input type="hidden" id="cinemaName" name="cinemaName" value="">
    <input type="hidden" id="filmId" name="filmId" value="">
    <input type="hidden" id="cinemaId" name="cinemaId" value="">
    <hr>
    <div id="search_sche_list_container">
        <div style="text-align:right">
        <button id="save_sche" type="button" class="btn btn-primary">添加</button>
            </div>
        <table id="search_sche_container" class="table-bordered" style="width: 100%;height: 100%;">
            <tr>
                <th><label><input type="checkbox" id="chkAll" />全选</label></th>
                <th>电影名称</th>
                <th>影院名称</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>厅</th>
                <th>类型</th>
                <th>语言</th>
            </tr>
        </table>
    </div>
</div>


<script>
    $(function () {
        //提交信息
        $("#save_sche").click(function(){
            var filmName = $('#filmName').val();
            var cinemaName = $('#cinemaName').val();
            var filmId = $('#filmId').val();
            var cinemaId = $('#cinemaId').val();
            var aid = $('#a_id').val();
            var startTime = [];
            var endTime = [];
            var sType = [];
            var room = [];
            var mpid = [];
            var i=-1;
            $("[do='chkList']:checked").each(function(t){
                var thisRow = $(this);
                startTime[t] = thisRow.attr("start_unixtime");
                endTime[t] = thisRow.attr("end_unixtime");
                sType[t] = thisRow.attr("itype");
                room[t] = thisRow.attr("roomname");
                mpid[t] = thisRow.val();
                i = t;
            });
            if(i<0){
                alert("error");
            }else{
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:'',
                    data:{
                        mpid:mpid,
                        room:room,
                        sType:sType,
                        endTime:endTime,
                        startTime:startTime,
                        cinemaId:cinemaId,
                        cinemaName:cinemaName,
                        filmId:filmId,
                        filmName:filmName,
                        aid:aid
                    },
                    success:function(data){
                        alert(data.msg);
                        if(data.ret == 0){
                            window.location.href = '/starActive/detail/'+aid;
                        }
                    }

                });

            }
            return false;

        });
        //ajax获取排期
        $("#search_sche_button").click(function(){
            //该死的浏览器兼容性
            var ret = true;
            var key_cinema = $("#key_cinema").val();
            var key_film = $("#key_film").val();
            var dateK = $("#key_date").val();
            if(key_cinema == ''){
                alert('影院id不能为空');
                $("#key_cinema").focus();
                return false;
            }
            if(key_film == ''){
                alert('影片id不能为空');
                $("#key_film").focus();
                return false;
            }
            if(isNaN(key_cinema)){
                alert('影院id必须是数字');
                $("#key_cinema").focus();
                return false;
            }
            if(isNaN(key_film)){
                alert('影片id必须是数字');
                $("#key_film").focus();
                return false;
            }

            $.getJSON("<?php echo $this->createUrl('starActive/searchSche')?>","cinemaId="+key_cinema+"&filmId="+key_film+"&dateK="+dateK,function(info){

                $("[do='sche_list']").remove();
                if(info.ret == 0){
                    $('#filmName').val(info.movieName);
                    $('#cinemaName').val(info.cinemaName);
                    $('#filmId').val(info.movieId);
                    $('#cinemaId').val(info.cinemaId);
                    //开始轮训排期，获取指定数据
                    var str ='';
                    $.each(info.data,function(idx,item){
                        str += getStr(item,info.movieName,info.cinemaName);
                    })
                    $('#search_sche_container').append(str);

                }else{
                    alert(info.msg);
                    return false;
                }
                return false;
            });
            return false;

        });

        //全选，取消全选
        $("#chkAll").click(function(){
            var typeList = false;
            if($(this).is(':checked')){
                typeList = true;
            }
            $("[do='chkList']").each(function(){
                $(this).prop("checked",typeList);
            });
        });


        function getStr(item,movieName,cinemaName)
        {
            var str ='';
            str += '<tr do="sche_list">';
            str += '<td>';
            str += '<input do="chkList" type="checkbox" name=mpid[] value="'+item.mpid+'" lagu="'+item.lagu+'" start_unixtime="'+item.start_unixtime+'"  end_unixtime="'+item.end_unixtime+'"  itype="'+item.type+'" roomname="'+item.roomname+'"/>';
            str += '</td>';
            str +='<td>'+movieName+'</td>';
            str += '<td>'+cinemaName+'</td>';
            str += '<td>'+item.start_time+'</td>';
            str += '<td>' +item.end_time+'</td>';
            str +='<td>'+item.roomname +'</td>';
            str +='<td>' +item.type+'</td>';
            str += '<td>'+item.lagu+'</td>';
            str += '</tr>';
            return str;
        }

        //通过movieid获取影院名字
        $("#id").blur(function () {
            var movieId = $("#id").val();
            if(movieId == '')return false;
            $.ajax({
                data: "movieId=" + movieId,
                url: "/pee/getMovieNameByMovieId",
                type: "POST",
                dataType: 'json',
                async: false,
                success: function (data) {
                    var succ = data.succ;
                    var msg = data.msg;
                    if (succ == 1) {
                        $("#movie_name").val(msg);
                        $("#movie_names").val(msg);
                        $("#movie_name").text(msg);
                    } else {
                        $("#movie_name").val('');
                        $("#movie_names").val('');
                        alert(msg);
                    }
                },
                error: function ($data) {
                    alert("网络错误请重试")
                }
            });
        });
        //点击继续添加尿点
        //点击删除尿点
        $("body").delegate('[do=del_pee]','click',function()
        {
            if($("[do=del_pee]").length <= 1){
                alert('最少也要保留一个尿点一个');
                return false;
            }
            var the_a=$(this);
            var v=$(this).attr("v");
            if(v== ""){
                the_a.parent().parent().parent().parent().remove();
                $(".niaodian:last").parent().find(">label").text("内容");
            }else{
                $.post("<?php echo $this->createUrl('pee/delPee')?>","pid="+v+"",function(a){
                    if(a==1){
                        the_a.parent().parent().parent().parent().remove();
                        $(".niaodian:last").parent().find(">label").text("内容");
                    }else{
                        alert(a);
                    }
                })
            }
        });



    });
</script>

<?php $this->endWidget(); ?>
