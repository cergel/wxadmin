<?php
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$form=$this->beginWidget('CActiveForm', array(
'id'=>'music-form',
'enableAjaxValidation'=>false,
'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal', 'name' => "BasicInfo")
)); ?>
<style media="screen">
    .musicinfo{
        border:1px solid #ccc;
        height:62px;
        width: 400px;
        padding: 5px;;
    }
    .musicinfo .img{
        float: left;
        width: 50px;
        height: 50px;
        border-radius:50%;
        overflow:hidden;
    }
    .musicinfo .txt{
        margin-left:5px ;
        float: left;
        height: 50px;
    }
    .musicinfo .txt p{
        font-size: 14px;
        height: 25px;
        width:310px;
        overflow: hidden;
        line-height: 25px;
        display: block;
        color: #0d0d0d;
    }
    .musicinfo .txt{
        line-height: 20px;
        width:310px;
        overflow: hidden;
        color:#ccc;
    }
    .musicinfo .del{
        float: right;
        width: 20px;
        height:50px
    }
    .musicinfo .close{
         background: #ccc;
         color: #000;
         border-radius: 12px;
         line-height: 20px;
         text-align: center;
         height: 20px;
         width: 20px;
         font-size: 18px;
         padding: 1px;
     }
    .page_bar{
        height: 30px;
        line-height: 30px;
        float:right;
    }
    .page_bar span{
        cursor: pointer;
        margin-left:5px;
        margin-right: 5px;
    }
    .page_bar .jumppage{
        width:30px;
        height:25px;
    }
    .close::before {
        content: "\2716";
    }
    label,.goon{
        text-align: right;
    }
    label i{
        color:red;

    }
    .form-group{
        display: block;
        clear: both;
        margin: 10px;
        height: 80%;
        overflow: auto;
    }
    .tip{
        line-height: 34px;
        color:#ff9232;
    }
    .line{
        width:100%;
        height: 2px;
        background:#e2e2e2;
        display: block;
        margin-bottom: 20px;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if(empty($model->id)){ ?>
                    <?php echo $form->textField($model, 'id', array('id' => 'id', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6')); ?>
                <?php }else{   ?>
                    <?php echo $form->textField($model, 'id', array('id' => 'id', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6','disabled'=>"disabled")); ?>
                    <input type="hidden" id="id" name="MovieMusic[id]" value=<?php echo $model->id;?>>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'movie_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'movie_name', array('id' => 'movie_name', 'size' => 60, 'maxlength' => 20, 'class' => 'col-xs-6','readonly'=>"readonly")); ?>
            </div>
        </div>
        <!--封面图片-->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'cover', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php if(!$model->isNewRecord) {
                    if (!empty($model->cover)) {
                        ?>
                        <div style="height:100px;width:100px;">
                            <img src="/uploads/MovieMusic/<?php echo $model->cover; ?>" height="100"/>
                        </div>
                    <?php }
                }?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'cover',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                    <span class="help-inline col-xs-5">
								<span class="middle">请上传100px*100px的专辑封面</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="songlist">
        <!-- 音乐内容开始-->
        <?php if($model->isNewRecord || empty($model->songlists)){ ?>
        <?php }else { ?>
        <?php  foreach($model->songlists as $key=>$val){  ?>
                <div class="form-group" data-id="<?php echo $val->song_id; ?>">
                    <label class="col-sm-3 control-label no-padding-right required" for="MovieMusic_cover">
                        歌曲
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="musicinfo" do="peeList" >
                            <div class="img"><img src="<?php echo $val->singer_pic; ?>" width="50" height="50"></div>
                            <div class="txt">
                                <p><?php echo $val->song_name; ?></p>
                                <?php echo $val->singer_name; ?>-<?php echo $val->album_name; ?>
                            </div>
                            <input type="hidden" name="song_id[]" value="<?php echo $val->song_id; ?>"/>
                            <input type="hidden" name="singer_pic[]" value="<?php echo $val->singer_pic; ?>"/>
                            <input type="hidden" name="song_name[]" value="<?php echo $val->song_name; ?>"/>
                            <input type="hidden" name="singer_name[]" value="<?php echo $val->singer_name; ?>"/>
                            <input type="hidden" name="album_name[]" value="<?php echo $val->album_name; ?>"/>
                            <input type="hidden" name="album_id[]" value="<?php echo $val->album_id; ?>"/>
                            <div class="del">
                                <span class="close"></span>
                            </div>
                        </div>
                    </div>
                </div>
        <?php } } ?>
            <!-- 音乐内容结束-->
        </div>
        <div class="clearfix">
            <div class="col-md-offset-3 col-md-9">
                <button id="btn" class="btn btn-info btn-sm" type="button" do="dialogInfo" href="/movieMusic/musicSearch" >
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    选择歌曲
                </button>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'status',array('1' => '开启', '0' => '关闭'), array('separator' => '  ')); ?>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div>

</div>
<div id="dialog" style="display:none"></div>
<script>
    $(function () {
        /*
        $(document).on('click', '[do=dialogInfo]', function(){
            //$('#dialogInfo').html($(this).attr('href'));
            var url=$(this).attr('href');
            var eee = $("<div ><iframe frameborder='0' scrolling='no' height='100%' width='100%' src='"+url+"'/></div>").dialog({
                autoOpen:false,
                height:600,
                width:600,
                modal:true, //蒙层（弹出会影响页面大小）
                title:'音乐选择',
                overlay: {opacity: 0.5, background: 'black' ,overflow:'auto'},
                buttons:{
                    '确定':function(){
                        $(this).dialog('close');
                    }
                }});
            eee.dialog('open');
            return false;
        });
        */
        ////////////////////弹窗内的js
        var curpage=1;
        var musicjsonobj= new Object();
        //提交信息
        $(document).on('click', '#save_sche', function() {
            var selected_song_id=new Array();
            var joined_song_id=new Array();
            $("[do='chkList']:checked").each(function(t){
                var thisRow = $(this);
                selected_song_id.push(parseInt(thisRow.val()));
            });
            var html='';
            //console.log(selected_song_id);
            //console.log(musicjsonobj.length);
            var song_index=$(".songlist .form-group").length;
            var j=1;
            for(var i =0;i<musicjsonobj.length;i++) {
                var jsonobj = musicjsonobj[i];
                //将已经选择的加入数组
                $(".songlist .form-group").each(function(){
                    var data=parseInt($(this).attr('data-id'));
                    if(joined_song_id.indexOf(data)==-1){
                        joined_song_id.push();
                    }
                });
                //当勾选的歌曲在json数组中并且这个勾选的歌曲没有加入列表的时候
                if(selected_song_id.indexOf(jsonobj.song_id)!=-1 && joined_song_id.indexOf(jsonobj.song_id)==-1){
                    var this_song_index=song_index+j;
                    //var songinfo=jsonobj.singer_pic+'|'+jsonobj.album_name+'|'+jsonobj.album_pic+'|'+jsonobj.playable+'|'+jsonobj.song_id+'|'+jsonobj.singer_name+'|'+jsonobj.song_name+'|'+jsonobj.song_play_url_cc+'|'+jsonobj.song_play_url_ws+'|'+jsonobj.song_size+'|'+jsonobj.song_time;
                    html+='<div class="form-group" data-id="'+jsonobj.song_id+'"><label class="col-sm-3 control-label no-padding-right required" for="MovieMusic_cover">';
                    html+='歌曲'+this_song_index+'<span class="required">*</span></label><div class="col-sm-9"><div class="musicinfo" do="peeList" >';
                    html+='<div class="img"><img src="'+jsonobj.singer_pic+'" width="50" height="50"></div>';
                    html+='<div class="txt"><p>'+jsonobj.song_name+'</p>'+jsonobj.singer_name+'-'+jsonobj.album_name+'</div>';
                    html+='<input type="hidden" name="song_id[]" value="'+jsonobj.song_id+'"/><input type="hidden" name="singer_pic[]" value="'+jsonobj.singer_pic+'"/><input type="hidden" name="song_name[]" value="'+jsonobj.song_name+'"/><input type="hidden" name="singer_name[]" value="'+jsonobj.singer_name+'"/><input type="hidden" name="album_name[]" value="'+jsonobj.album_name+'"/><input type="hidden" name="album_id[]" value="'+jsonobj.album_id+'"/><input type="hidden" name="pre_play_url[]" value="'+jsonobj.song_play_url_cc+'"/>';
                    html+='<div class="del"><span class="close"></span></div></div></div></div>';
                    j++;
                }
            }
            $(".songlist").append(html);
            return false;
        });
        $(document).on('click', '.form-group .close', function() {
            $(this).parent().parent().parent().parent().remove();
            return false;
        });
        var search=function(page){
            var key_name = $("#key_name").val();
            if(key_name == ''){
                alert('音乐关键字不能为空');
                $("#key_name").focus();
                return false;
            }
            var url='/movieMusic/musicKeyWords';
            var data={
                'keywords':key_name,
                'p':page,
            };
            $.get(url,data,function(response){
                $("[do='sche_list']").remove();
                //response==eval("("+response+")");//转换为json对象
                //response=jQuery.parseJSON(response);
                if(response.ret == 0){
                    //开始轮训排期，获取指定数据
                    var str ='';
                    musicjsonobj=response.data;//将结果赋给对象
                    $.each(response.data,function(idx,item){
                        str += getStr(item);
                    });
                    var total=response.total_num;//记录数
                    var pagesize=response.cur_num;//每页记录数
                    var page=response.page_index;//当前页
                    var pages=Math.ceil(total/pagesize);
                    $('#search_sche_container').append(str);
                    var prehtml='';
                    var nexthtml='';
                    if(page!=1){
                        prehtml="<span class='pre'><</span>";
                    }
                    if(page!=pages){
                        nexthtml="<span class='next'>></span>";
                    }
                    var pager_bar=prehtml+page+'/'+pages+nexthtml;
                    $('.page_bar').html(pager_bar);
                    $('.page_bar .pre').on('click',function(){
                        curpage--;
                        search(curpage);
                    });
                    $('.page_bar .next').on('click',function(){
                        curpage++;
                        search(curpage);
                    });
                    $('.page_bar .searchjumpbtn').on('click',function(){
                        var jumppage=$(".page_bar .jumppage").val();
                        jumppage=parseInt(jumppage);
                        if(jumppage>0 && jumppage<=pages) search(jumppage);
                    });
                }else{
                    alert(info.msg);
                    return false;
                }
                return false;
            },'json');
        }
        //ajax获取音乐
        $(document).on('click', '#search_sche_button', function() {
            search(1);
            return false;
        });
        $(document).on('click', '#chkAll', function() {
            var typeList = false;
            if($(this).is(':checked')){
                typeList = true;
            }
            $("[do='chkList']").each(function(){
                $(this).prop("checked",typeList);
            });
        });
        function getStr(item)
        {
            var playhtml='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="25" height="20" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab"> <param name="movie" value="/assets/singlemp3player.swf?file='+item.song_play_url_cc+'&backColor=990000&frontColor=ddddff&repeatPlay=false&songVolume=30" /><param name="wmode" value="transparent" /><embed wmode="transparent" width="25" height="20" src="/assets/singlemp3player.swf?file='+item.song_play_url_cc+'&backColor=990000&frontColor=ddddff&repeatPlay=false&songVolume=30" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
            var str ='';
            str += '<tr do="sche_list">';
            str += '<td>';
            str += '<input do="chkList" type="checkbox" name=musicid[] value="'+item.song_id+'">';
            str += '</td>';
            str +='<td>'+item.song_name+'</td>';
            str +='<td>'+item.singer_name+'</td>';
            str +='<td>'+item.album_name+'</td>';
            str += '<td>'+item.song_size+'</td>';
            str += '<td>'+(item.song_time/60).toFixed(2)+'</td>';
            str += '<td>'+playhtml+'</td>';
            str += '</tr>';
            return str;
        }
        ////////////////////弹窗内的jsend
        var dialog1 = $("#dialog").dialog({
            autoOpen: false,
            height:600,
            width:600,
            modal:true,
            title:'音乐选择',
            buttons:{
                '关闭':function(){
                    $(this).dialog('close');
                }
            }
        });
        $(document).on('click', '[do=dialogInfo]', function() {
            var url=$(this).attr('href');
            dialog1.load(url + ' .page-content').dialog('open');
        });
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

        $("#submit").click(function(){
            if(!$("#id").val()){
                alert('请输入影片id');
                return false;
            }
            if(!$("#movie_name").val()){
                alert('请输入影片名称异常');
                ret = false;
                return false;
            }
            return true;
        })

    });
</script>


<?php
$this->endWidget();
?>
