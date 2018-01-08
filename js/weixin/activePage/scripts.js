// 活动搜索功能
function search_activity() {
    $.ajax({
        url:'/weixin/activePage/_activity',
        method:'get',
        dataType:'json',
        data:'keyword='+$('#activity-search-text').val(),
        beforeSend:function(){
            if ($('#activity-search-text').val().length<1) {
                alert('请输入关键字');
                $('#activity-search-text').focus();
                return false;
            }
            return true;
        },
        success:function(data) {
            var html = '';
            if (data.length > 0) {
                for (i in data) {
                    if ($('#activities :checkbox[value='+data[i].iResourceID+']').length === 0) {
                        html += '<div class="col-sm-3"><div>'
                        html += '<p><input type="checkbox" name="iResourceID[]" value="' + data[i].iResourceID + '" />'
                        html += 'ID:' + data[i].iResourceID + '</p>'
                        html += '<p>' + data[i].sBonusName + '</p>'
                        html += '</div></div>'
                    } else {
                        $('#activities :checkbox[value='+data[i].iResourceID+']').closest('div').css('background-color','#ffbfbf');
                    }
                }
            } else {
                html = '<p style="color:red">搜索结果为空</p> ';
            }

            setTimeout(function () {$('#activities :checkbox').closest('div').css('background-color','#f7f7f7')} , 3000)
            $('#activity-search-result').html(html);
        }
    });
}
$(function(){
    // 支持回车搜索
    $('#activity-search-text').keydown(function(e)
    {
        if(e.keyCode==13)
        {
            e.preventDefault();
            return false;
        }
    }).keyup(function(e)
    {
        if(e.keyCode==13)
        {
            search_activity();
            e.preventDefault();
            return false;
        }
    });
    // 点击按钮搜索
    $('#activity-search-button').click(function(){
        search_activity();
    })
    // checkbox
    $('#activity-search-result').on('click', ':checkbox', function () {
        $(this).closest('div.col-sm-3').appendTo('#activities');
    });
})
