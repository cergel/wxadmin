<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'全国热映影片列表',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
    });
");
?>

<div class="page-header">
    <h1>全国热映影片列表</h1>
</div>


<div class="row">
    <div class="col-xs-12">
        <?php echo CHtml::dropDownList( 'citys', $selected, $data);  ?>
        <input id="search" type="button" name="getList" class="btn btn-success" value="筛选"/>
        <div id="movielists">
            <table id="movielist" class="items table table-striped table-bordered table-hover dataTable"></table>
        </div>
    </div>
    <div id="dialogInfo" style="display:none">111</div>
</div>
<script>
    $(function(){
        $("#search").click(function(){
            var cityid = $("#citys").find("option:selected").val();
            var html ='<tr><th>影片ID</th><th>影片名称</th><th>上映时间</th></tr>';
             $.ajax({
                url: '/HotMovie/getHotMovie?cityid=' + cityid,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.ret == 0) {
                         $('#movielist').html('');
                        $.each(data.data,function(i,item){
//                            alert(item.movieNo);
                        html += '<tr><td>'+ item.movieNo +'</td><td>'+item.movieName+'</td><td>'+item.movieDate+'</td></tr>';
                        });
                        $('#movielist').html(html);
                    }else {
                        alert(data.msg);
                    }
                },
                error: function (msg) {
                    alert('网络异常请重试');
                }
             })
        })
       
    })
</script>



<?php
