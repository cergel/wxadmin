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
    <div class="input-group col-xs-12" style="margin-top: 5px;margin-top: 5px;">
        <button id="queryInfo" class="btn btn-default" type="button"
                style="height: 34px;line-height: 12px;">查询
        </button>
    <div class="pull-right">
        <button id="queryInfoExcel" class="btn btn-default" type="button"
                style="height: 34px;line-height: 12px;">导出excel
        </button>
        </div>
    </div>

    <br/>
    <table class="table table-bordered">
        <tr>
            <th>采购时间</th>
            <th>采购id</th>
            <th>影院id</th>
            <th>排期id</th>
            <th>座位</th>
            <th>状态</th>
            <th>放映日期</th>
        </tr>
        <?php
            if(!empty($data)){
                foreach($data as $v){
                    echo "
                        <tr>
                        <th>{$v->created_time}</th>
                        <th>{$v->fix_order_id}</th>
                        <th>{$v->cinema_no}</th>
                        <th>{$v->schedule_id}</th>
                        <th>{$v->seat}</th>
                        <th>{$v->local_seat_status}</th>
                        <th>{$v->show_time}</th>
                        </tr>
                    ";
                }
            }
            ?>

    </table>
    </table>
<?php
Yii::app()->getClientScript()->registerScriptFile("/js/goldSeat.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
?>