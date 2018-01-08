$(function(){
    $('.date-timepicker').datetimepicker({
        format:"YYYY-MM-DD hh:mm:ss"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

})
