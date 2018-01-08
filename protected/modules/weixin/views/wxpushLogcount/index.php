<?php
$this->breadcrumbs = array(
    '评论推送发送量'
);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
?>
<div class="page-header">
    <h1>评论推送发送量</h1>
</div>

<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
    table.hovertable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #999999;
        border-collapse: collapse;
    }
    table.hovertable th {
        background-color:#c3dde0;
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9c6c9;
    }
    table.hovertable tr {
        background-color:#d4e3e5;
    }
    table.hovertable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9c6c9;
    }
</style>

<!-- Table goes in the document BODY -->
<table class="hovertable">
    <tr>
        <th>日期</th>
        <th>推送量</th>
    </tr>
    <?php
    foreach ($data as $date => $count) {
        echo "<tr onmouseover=\"this.style.backgroundColor='#ffff66';\" onmouseout=\"this.style.backgroundColor='#d4e3e5';\">";
        echo "<td>" . $date . "</td>";
        echo "<td>" . $count . "</td>";
        echo "</tr>";
    }
    ?>
</table>







