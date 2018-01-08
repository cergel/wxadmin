<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYYMMDD\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$this->breadcrumbs = array(
    '评论统计',
);
?>
<form name="Statistics" action="/index.php/statistics/index" method="get">
    <div class="search_box" width="100%" style="margin-top:20px">
        <div id="start_time" height="50px" width="40%" style="margin-left: 50px">
            <span style="font-weight: normal;font-size: 16px">开始时间：</span><input type="text" name="start_time" class="date-timepicker" value="<?php echo $start_time ?>">
            <span style="font-weight: normal;font-size: 16px;margin-left: 43px">结束时间：</span><input type="text" name="end_time" class="date-timepicker" value="<?php echo $end_time ?>">
            <span style="font-weight: normal;font-size: 16px;margin-left: 30px">渠道：</span>
            <select name="channel">
                <option value="" <?php if(empty($channel))echo 'selected'; ?>>全部</option>
                <option value="3" <?php if($channel == 3)echo 'selected';?>>微信电影票</option>
                <option value="8" <?php if($channel == 8)echo 'selected';?>>IOS</option>
                <option value="9" <?php if($channel == 9)echo 'selected';?>>Android</option>
                <option value="28" <?php if($channel == 28)echo 'selected';?>>手Q</option>
            </select>
            <select name="type">
                <option value="" <?php if(empty($type))echo 'selected'; ?>>查看</option>
                <option value="1" <?php if($type == 1)echo 'selected';?>>导出</option>
            </select>
            <input type="submit" name="chaxun" value="查询" style="margin-left: 10px">
        </div>
        <div style="margin-top:20px"><span style="color: #ff0a0a;margin-left: 50px;">填写日期格式为20160602,时间间隔不能超过31天</span></divmargin-top:20px>
    </div>
    <div width="100%" style="margin-top: 40px">
        <table class="result_table" style="margin:0 auto;font-weight: normal;color:#3ca0d9;font-size: 16px;border:solid 1px #3ca0d9" width="1200px" border="1">
            <tr>
                <td align="center">时间</td>
                <td align="center">看过量</td>
                <td align="center">想看量</td>
                <td align="center">评论总量</td>
                <td align="center">push评论量</td>
                <td align="center">购票评论</td>
                <td align="center">评论回复总量</td>
                <td align="center">点赞总量</td>
                <td align="center">超赞</td>
                <td align="center">不错</td>
                <td align="center">一般</td>
                <td align="center">睡着</td>
                <td align="center">失望</td>
                <td align="center">烂片</td>
                <td align="center">表情合计</td>
            </tr>
            <?php foreach ($searchResult as $key => $result): ?>
                <tr>
                    <td  align="center"><?php echo $key ?></td>
                    <td align="center"><?php echo $result['seenCount'] ?></td>
                    <td align="center"><?php echo $result['wantCount'] ?></td>
                    <td align="center"><?php echo $result['commentCount'] ?></td>
                    <td align="center"><?php echo $result['pushCount'] ?></td>
                    <td align="center"><?php echo $result['purchaseCount'] ?></td>
                    <td align="center"><?php echo $result['replyCount'] ?></td>
                    <td align="center"><?php echo $result['favorCount'] ?></td>
                    <td align="center"><?php echo $result['score_1'] ?></td>
                    <td align="center"><?php echo $result['score_2'] ?></td>
                    <td align="center"><?php echo $result['score_3'] ?></td>
                    <td align="center"><?php echo $result['score_4'] ?></td>
                    <td align="center"><?php echo $result['score_5'] ?></td>
                    <td align="center"><?php echo $result['score_6'] ?></td>
                    <td align="center"><?php echo $result['scoreCount'] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</form>
