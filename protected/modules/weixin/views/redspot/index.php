<?php
$this->breadcrumbs = array(
    '导流红点'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
");
?>
<script>
    var change_color = function (t) {
        t.style.color = '#000'; //黑色
    }
</script>
<div class="page-header">
    <h1>红点后台</h1>
</div>
<form name="input" action="" method="get">
    <p>投放位置（目前仅限首页-演出票入口）</p>

    <p>范围（目前仅限未购买演出票用户）</p>

    <p>开始时间： <input type="text" id="starttime" name="starttime" value="<?php echo $data['starttime']; ?>"
                    onclick="document.getElementById('starttime').value = '';" class="date-timepicker"
                    onfocus="change_color(this)"/>
        (格式：2016-09-09 00:00:00)</p>

    <p>结束时间： <input type="text" id="endtime" name="endtime" value="<?php echo $data['endtime']; ?>"
                    onclick="document.getElementById('endtime').value = '';" class="date-timepicker"
                    onfocus="change_color(this)"/>
        (格式：2016-09-09 00:00:00)</p>

    <p>索引ID： <input type="text" name="indexid" value="<?php echo $data['indexid']; ?>" readonly="true">
        (索引ID用于前端判断是否是同一次配置，每次修改ID会加1)
    </p>

    <p>是否开启：<input type="checkbox" name="status" <?php echo (!empty($data['status'])) ? 'checked' : '' ?>/>
    </p>
    <input type="hidden" name="opType" value="add">
    <input type="submit" value="创建" class="btn btn-success"/>
</form>
