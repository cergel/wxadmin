<?php
$this->breadcrumbs = array(
    $this->module->id,
    '图标资源'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);

?>
<table class="items table table-striped table-bordered table-hover ">
        <tr>
            <td>分类</td><td>操作</td>
        </tr>
    <tr>
        <td>加载动效图</td><td><a href="/weixin/icon/loading_index">修改</a></td>
    </tr>
<!--    还未测试 暂时注释掉-->
<!--    <tr>-->
<!--        <td>电影票导航图标</td><td><a href="/weixin/icon/movie_index">修改</a></td>-->
<!--    </tr>-->
</table>