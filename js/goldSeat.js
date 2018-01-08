/**
 * Created by dongzj on 15/11/4.
 */
$(function () {
    $(document).on('click', '#search', searchMovie);
    $(document).on('click', '#search_cinema', searchCinema);
    $(document).on('click', '#add_to_restrict', addMovieRestrict);
    $(document).on('click', '.del_restrict_movie', delMovieRestrict);
    $(document).on('click', '#open_lock', openLockStatus);
    $(document).on('click', '#close_lock', closeLockStatus);

    $(document).on('click', '#open_restrict', openRestrictStatus);
    $(document).on('click', '#close_restrict', closeRestrictStatus);

    $(document).on('click', '#restrict_all_movie', closeRestrictMovie); //全部影片
    $(document).on('click', '#restrict_movie', restrictMovie); //仅限影片
    $(document).on('click', '#restrict_other_movie', restrictOtherMovie); //排除影片

    $(document).on('click', '#restrict_all_cinema', closeRestrictCinema); //全部影院
    $(document).on('click', '#restrict_cinema', restrictCinema); //仅限影院

    $(document).on('change', '#lock_seat_count', changeLockLimit); //修改锁座数量

    $(document).on('click', '#save_all_cinema', addCinemaRestrict);
    $(document).on('click', '.del_cinema_restrict', delCinemaRestrict);

    $(document).on('click', '#save_time', saveRestrictTime); //保存售卖时间

    $(document).on('click', '#search_cinema_lock_count', searchCinemaLockCount); //查询影院锁座数量

    $(document).on('click', '#init_time', initTime); //带时间查询商品中心

    $(document).on('click','#save_buy_limit',buyLimit);//保存购买限制

    $(document).on('click', '#queryInfo', queryInfo); //查询自有库存详情

    $(document).on('click', '#queryInfoExcel', queryInfoExcel); //查询自有库存详情--导出excel

    $(".form-group").height(40);

    $('.data_date1-timepicker').datetimepicker({
        format:'YYYY-MM-DD'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
});

var restrictMovieNo = '',
    searchCinemaStr = '',
    searchCinemaNoStr = '';

var searchMovie = function () {
    var $keyword = $("#searchInput").val();
    var url = "/index.php/goldSeat/searchMovie";
    var data = {keyword: $keyword};
    $.ajax({
        url: url,
        data: data,
        type: "post",
        dataType: "json",
        success: function (json) {
            console.log(json);
            $("#movie_result_container").show();
            if (json.length == 0) {
                $("#movie_result_container").html('没有搜索结果');
            } else {
                var html = '';
                for (n in json) {
                    html += '<div class="col-sm-2 center middle" style="height:30px;line-height: 30px;"> ' +
                    '<input id="movie_result_' + json[n].id + '" class="movie_result" type="checkbox" value="' + json[n].id + '"/> ' +
                    '<label for="movie_result_' + json[n].id + '" style="font-size: 12px;">' + json[n].movieName + '</label> ' +
                    '</div>';
                }
                html += '<div class="col-sm-2 center"> <button id="add_to_restrict" class="btn btn-success">添加到列表</button> </div>';
                $("#movie_result_container").html(html);
            }
        }
    });
};

var searchCinema = function () {
    var $city = $("#city-selector-selected").html();
    var $keyword = $("#key_cinema").val();
    var url = "/index.php/goldSeat/searchCinema";
    var data = {city: $city, keyword: $keyword};
    $.ajax({
        url: url,
        data: data,
        type: "post",
        dataType: "json",
        success: function (json) {
            $('.cinema_list').remove();
            var html = '';
            searchCinemaStr = '',
            searchCinemaNoStr = '';
            for (n in json) {
                searchCinemaStr += json[n].CinemaName + ',';
                searchCinemaNoStr += json[n].CinemaNo + ',';
                html += '<tr class="cinema_list"><td>' + json[n].CinemaNo + '</td><td>' + json[n].CinemaName + '</td><td>' + json[n].TheaterChain + '</td></tr>';
            }
            if (html == '') {
                html += '<tr class="cinema_list"><td colspan="3">没有搜索结果</td></tr>';
            }
            $("#cinema_list_container").append(html);
        }
    });
};
var addMovieRestrict = function () {
    var $checkedObj = $('.movie_result:checked');
    $checkedObj.each(function (i) {
        restrictMovieNo += $(this).val() + ',';
    });
    console.log(restrictMovieNo);
    var url = "/index.php/goldSeat/addMovie";
    var data = {movieNos: restrictMovieNo};
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            if (json.result) {
                var html = '';
                for (n in json.restrictMovies) {
                    html += '<div id="restrict_movie_container_'+json.restrictMovies[n].MovieNo+'" class="col-sm-2 center" style="height:30px;line-height: 30px;"> ' +
                                '<span>'+json.restrictMovies[n].MovieNameChs+'</span> ' +
                                '<a movieid="'+json.restrictMovies[n].MovieNo+'" class="fa fa-minus-circle del_restrict_movie"></a> ' +
                            '</div>';
                }
                $("#restrict_movie_container").html(html);
            }
            restrictMovieNo = '';
            $('.movie_result:checked').prop('checked', false);
        }
    });
};

var delMovieRestrict = function(){
    var $movieId = $(this).attr('movieId');
    var url = "/index.php/goldSeat/delMovie";
    var data = {movieNo: $movieId};
    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json){
            if(json.result){
                var html = '';
                for (n in json.restrictMovies) {
                    html += '<div id="restrict_movie_container_'+json.restrictMovies[n].MovieNo+'" class="col-sm-2 center" style="height:30px;line-height: 30px;"> ' +
                                '<span>'+json.restrictMovies[n].MovieNameChs+'</span> ' +
                                '<a movieid="'+json.restrictMovies[n].MovieNo+'" class="fa fa-minus-circle del_restrict_movie"></a> ' +
                            '</div>';
                }
                $("#restrict_movie_container").html(html);
            }
        }
    });
};

var addCinemaRestrict = function(){
    var url = "/index.php/goldSeat/addCinema";
    var data = {cinemaNos: searchCinemaNoStr, cinemaNames: searchCinemaStr};
    var html = '<tr> ' +
                    '<th>影院</th> ' +
                    '<th>操作</th> ' +
                '</tr> ';
    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json){
            if(json.result){
                for(n in json.restrictCinemas){
                    html += '<tr> ' +
                                '<td>'+json.restrictCinemas[n].cinema_name+'</td> ' +
                                '<td><a class="del_cinema_restrict" value="'+json.restrictCinemas[n].cinema_no+'" href="javascript:;">删除</a></td> ' +
                            '</tr>'
                }
                $("#restrict_cinema_container").html(html);
                $("#myModal").modal('hide');
            }
        }
    });
};

var delCinemaRestrict = function(){
    var url = "/index.php/goldSeat/delCinema";
    var cinemaNo = $(this).attr('value');
    var data = {cinemaNo: cinemaNo};
    var html = '<tr> ' +
        '<th>影院</th> ' +
        '<th>操作</th> ' +
        '</tr> ';
    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json){
            if(json.result){
                for(n in json.restrictCinemas){
                    html += '<tr> ' +
                    '<td>'+json.restrictCinemas[n].cinema_name+'</td> ' +
                    '<td><a class="del_cinema_restrict" value="'+json.restrictCinemas[n].cinema_no+'" href="javascript:;">删除</a></td> ' +
                    '</tr>';
                }
                $("#restrict_cinema_container").html(html);
            }
        }
    });
};
var changeRestrictStatus = function(status, type){
    var url = "/index.php/goldSeat/changeRestrict";
    var data = {status: status, type: type};
    $.ajax({
        url :url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json){
            console.log(json);
        }
    });
};
//总过滤开关
var openRestrictStatus = function(){
    changeRestrictStatus(1, 'restrict');
};
var closeRestrictStatus = function(){
    changeRestrictStatus(0, 'restrict');
};

//是否锁座开关
var openLockStatus = function(){
    changeRestrictStatus(1, 'isLock');
};
var closeLockStatus = function(){
    changeRestrictStatus(0, 'isLock');
};
//是否开启影片限制
var closeRestrictMovie = function(){
    changeRestrictStatus(0, 'movie');
};
var restrictMovie = function() {
    changeRestrictStatus(1, 'movie');
};
var restrictOtherMovie = function(){
    changeRestrictStatus(2, 'movie');
};
//是否开启影院限制
var closeRestrictCinema = function(){
    changeRestrictStatus(0, 'cinema');
};
var restrictCinema = function() {
    changeRestrictStatus(1, 'cinema');
};
//修改售卖退票时间
var saveRestrictTime = function(){
    var saleTime = $("#sale_time").val();
    var refundTime = $("#refund_time").val();
    saleTime = parseInt(saleTime);
    refundTime = parseInt(refundTime);
    //return false;
    if(refundTime >= saleTime)
    {
        alert("错误：退票时间必须小于售卖时间");
        return false;
    }
    if(refundTime <30 )
    {
        alert("错误：退票时间必须大于30分");
        return false;
    }
    changeRestrictStatus(saleTime, 'sale');
    changeRestrictStatus(refundTime, 'refund');
};
//修改锁座数量
var changeLockLimit = function(){
    var lock_seat_count = $("#lock_seat_count").val();
    lock_seat_count = parseInt(lock_seat_count);
    if(isNaN(lock_seat_count)){
        alert('失败：请填写数字');
        return false;
    }
    if(lock_seat_count<0){
        alert("失败：数量必须大于0");
        return false;
    }
    changeRestrictStatus( lock_seat_count, 'lockLimit');
};


//==================goods center==================
var searchCinemaLockCount = function(){
    var url = "/index.php/goldSeat/searchCinemaLockCount";
    var data = {'cinemaNo': $("#cinema_no").val(), 'startTime': $("#start_time").val(), 'endTime' : $("#end_time").val()}
    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json) {
            console.log(json.lock);
                $("#cinema_lock").html(json.lock);
                $("#cinema_sale").html(json.sale);
                $("#cinema_refund").html(json.refund);
                $("#cinema_order").removeClass('hidden');

        }
    });
};

var initTime = function(){
    var startTime = $("#start_time").val();
    var endTime = $("#end_time").val();
    var url = '/goldSeat/goodsCenter?startTime=' + startTime + '&endTime=' + endTime;
    location.href = url;
};

//购买限制数量
var buyLimit = function(){
    var nums = $("#buy_limit").val();
    var url = "/index.php/goldSeat/changeRestrict";
    var data = {status: nums, type: 'buyLimit'};

    nums = parseInt(nums);
    if(isNaN(nums)){
        alert('失败：请填写数字');
        return false;
    }
    if(nums<0 ){
        alert('失败：数量必须大于等于0');
        return false;
    }
    $.ajax({
        url :url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function(json){
            if(json==true){
                alert("保存成功");
            }else{
                alert("保存失败");
            }
            console.log(json);
        }
    });
}

var queryInfo = function (){
    var startTime = $("#start_time").val();
    var endTime = $("#end_time").val();
    var url = '/goldSeat/orderInfo?startTime=' + startTime + '&endTime=' + endTime;
    location.href = url;
}

var queryInfoExcel = function (){
    var startTime = $("#start_time").val();
    var endTime = $("#end_time").val();
    var url = '/goldSeat/orderInfoExcel?startTime=' + startTime + '&endTime=' + endTime;
    location.href = url;
}