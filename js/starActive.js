/**
 * Created by Administrator on 2016/3/6.
 */
$(function () {
    $(document).on('click', '#search_sche_button', searchSche);    //搜索排期

    $(document).on('click', '#save_all_sche', addSche); //添加排期

});

var searchSche=function() {
        var cinemaId = $('#key_cinema').val();
        var filmId = $('#key_film').val();
        var dateId = $('#key_date').val();
        var url = '/starActive/searchSche';
        var data = {s_cinema_id: cinemaId, s_movie_id: filmId,dateK:dateId};
    alert('123');return false;
        $.ajax({
            url: url,
            data: data,
            type: 'get',
            dataType: 'json',
            success: function (json) {
                $('.sche_list').remove();
                var html = '';
                for (n in json) {
                    html += '<tr class="sche_list"> <td><label><input type="checkbox" /></label></td> <td>' + json[n].start_time + '</td><td>' + json[n].end_time + '</td></tr>';
                }
                if (html == '') {
                    html += '<tr class="sche_list"><td colspan="3">没有搜索结果</td></tr>';
                }
                $("#search_sche_container").append(html);

            },
            failure:function (json) {

                alert('Failed');

            },
        });
}

function addSche() {

}