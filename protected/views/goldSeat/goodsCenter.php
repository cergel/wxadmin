<?php
/**
 * Created by PhpStorm.
 * User: dongzj
 * Date: 16/3/11
 * Time: 15:16
 */
$this->breadcrumbs = array(
    '商品中心',
);

?>
    <div class="input-group col-xs-3">
        <input id="start_time" value="<?= $startTime?>" type="text" placeholder="开始时间" class="form-control data_date1-timepicker"/>
    <span class="input-group-addon">
        <i class="fa fa-clock-o bigger-110"></i>
    </span>
    </div>
    <div class="input-group col-xs-3" style="margin-top: 5px;margin-top: 5px;">
        <input id="end_time" value="<?= $endTime?>" type="text" placeholder="结束时间" class="form-control data_date1-timepicker"/>
    <span class="input-group-addon">
        <i class="fa fa-clock-o bigger-110"></i>
    </span>
    </div>
    <div class="input-group col-xs-3" style="margin-top: 5px;margin-top: 5px;">
        <button id="init_time" class="btn btn-default" type="button"
                style="height: 34px;line-height: 12px;">查询
        </button>
    </div>

    <h3>总数量</h3>
    <table class="table table-bordered">
        <tr>
            <th>购买(张)</th>
            <th>售出(张)</th>
            <th>未卖出(张)</th>
            <th>退票成功(张)</th>
            <th>退票失败(张)</th>
            <th>售卖中(张)</th>
        </tr>
        <tr>
            <td><?php echo $orderCount['lock'] ?></td>
            <td><?php echo $orderCount['sale'] ?></td>
            <td><?php echo $orderCount['notSale'] ?></td>
            <td><?php echo $orderCount['refund'] ?></td>
            <td><?php echo $orderCount['badRefund'] ?></td>
            <td><?php echo $orderCount['onSale'] ?></td>
        </tr>
    </table>
    <h3>搜索影院</h3>

    <div class="col-lg-3" style="margin-bottom: 5px;">
        <div class="input-group">
            <input id="cinema_no" type="text" class="form-control" placeholder="输入影院编号">
      <span class="input-group-btn">
        <button id="search_cinema_lock_count" class="btn btn-default" type="button"
                style="height: 34px;line-height: 12px;">查询影院
        </button>
      </span>
        </div>
        <!-- /input-group -->
    </div>
    <!-- /.col-lg-6 -->

    <table id="cinema_order" class="table table-bordered hidden">
        <tr>
            <th>买入(张)</th>
            <th>售出(张)</th>
            <th>退票(张)</th>
        </tr>
        <tr>
            <td id="cinema_lock"></td>
            <td id="cinema_sale"></td>
            <td id="cinema_refund"></td>
        </tr>
    </table>
    <table id="cinema_unset" class="table table-bordered hidden">
        <tr>
            <th>没有查到该影院！！！</th>
        </tr>
    </table>
<?php
Yii::app()->getClientScript()->registerScriptFile("/js/goldSeat.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
?>