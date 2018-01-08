<?php

// 从mango读取全部影片数据
//$movies = BsonBaseMovie::model()->default()->findAll();
$movies = [];

Yii::app()->clientScript->registerScript('movie-selector', "
// 根据各项条件进行显示控制
refresh_movies = function(){
    var keyword = $('#movie-selector-".$name."-keyword').val();
    if (keyword)
        re = new RegExp(keyword, '');

    $('#movie-selector-".$name." .movies li').each(function(ii,item){
        var keyword_match = (keyword.length === 0 ? true : re.test($(this).attr('name')));

        if (keyword_match) {
            $(this).show().removeClass('movie-selector-hide').addClass('movie-selector-show');
        } else {
            $(this).hide().removeClass('movie-selector-show').addClass('movie-selector-hide');
        }
    });
}
// 显示已选中影片
update_selected_movies = function(){
    var selected = '点击设置影片';
    $('#movie-selector-".$name." .movies :checkbox:checked').each(function(){
        //console.log($(this).closest('li').attr('name'));
        if (selected != '点击设置影片')
            selected += ',';
        else
            selected = '';
        selected += $(this).closest('li').attr('name')
    });
    $('#movie-selector-selected').html(selected);
}

/* 工具栏 */
$('#movie-selector-".$name."-select').click(function(){
    $('#movie-selector-".$name." .movies .movie-selector-show input[type=checkbox]').prop('checked',true);
});
$('#movie-selector-".$name."-clear').click(function(){
    $('#movie-selector-".$name." .movies .movie-selector-show input[type=checkbox]').prop('checked',false);
});
$('#movie-selector-".$name."-search').click(function(){
    refresh_movies();
});
$('#movie-selector-selected').click(function(){
    $('#movie-selector-".$name."').show();
});
/* 确认按钮 */
$('#movie-selector-ok').click(function(){
    $('#movie-selector-".$name."').hide();
    update_selected_movies();
});
/* 回车事件处理 */
$('#movie-selector-".$name."-keyword').keydown(function(e){
    if(e.keyCode==13){
        e.preventDefault();
        return false;
    }
}).keyup(function(e){
    if(e.keyCode==13){
        e.preventDefault();
        refresh_movies();
        return false;
    }
});
update_selected_movies();
");
?>
<style>
    #movie-selector-<?php echo $name;?>{
        position: fixed;
        top:0;
        background:#000;
        z-index:2000;
        left:0;
        height:100%;
    }
    #movie-selector-selected{
        cursor:pointer;
        color:#999;
        line-height: 30px;
    }
    .movie-selector-widget .movies h3{
        padding:5px;
        font-weight:bold;
        width:100%;
        clear:both;
        font-size:14px;
        border-bottom: 1px solid #e0e0e0;
    }
</style>
<div class="row" id="movie-selector-selected">点击设置影片</div>
<div class="row movie-selector-widget" id="movie-selector-<?php echo $name;?>" style="display:none;">
    <div class="col-xs-8 col-xs-offset-2" style="background:#fff;margin-top:2em;height:90%;">
        <div style="min-height:39px;padding:5px;margin:0 -12px;">
            <div class="row">
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control search-query"  id="movie-selector-<?php echo $name;?>-keyword" placeholder="关键字" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-gray btn-sm" id="movie-selector-<?php echo $name;?>-search">
                            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                        </button>
                    </span>
                    </div>
                </div>
                <div class="col-xs-9">
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="movie-selector-<?php echo $name;?>-clear">清空当前</button>
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="movie-selector-<?php echo $name;?>-select">选中当前</button>
                </div>
            </div>
        </div>
        <div class="row" style="height:85%;">
            <div class="col-xs-12 movies" style="height: 100%; overflow-y: scroll; background:#efefef;">
                        <ul class="list-inline">
                            <?php foreach ($movies as $ck => $movie) { ?>
                                            <li class="col-xs-4 movie-selector-show" name="<?php echo $movie->MovieNameChs;?>"
                                    movieNo="<?php echo $movie->MovieNo;?>"
                                    >
                                    <input type="checkbox"
                                           name="<?php echo $name;?>[]"
                                           value="<?php echo $movie->MovieNo;?>"
                                        <?php echo in_array($movie->MovieNo, $this->selectedMovies) ? " checked='checked' " : "" ;?>
                                        />
                                    <?php echo $movie->MovieNameChs;?>
                                </li>
                            <?php } ?>
                        </ul>
            </div>
        </div>
        <div class="clearfix" style="background:#fff;padding:5px;margin:0 -12px;">
                <button class="btn btn-info btn-sm pull-right" type="button" id="movie-selector-ok">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    确定
                </button>
                <!--
                <button class="btn" type="reset" id="movie-selector-reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
                -->
        </div>
    </div>
</div>