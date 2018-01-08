<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:38
 */
/* @var $this ActiveController */
/* @var $dataProvider CActiveDataProvider */
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs = array(
    '消息中心',
);
?>
<div class="page-header">
    <table>
        <tr>
            <td>
                <h1></h1>
            </td>
            <td>
                <ul>
                    <li>提前24小时新建消息</li>
                    <li>消息预约后发送前可以更改内容,但是取消任务需要提前24小时进行</li>
                </ul>
            </td>
            <td>
                <a class="btn btn-success" href="/message/messageNotice/create">创建新的消息推送</a>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'movieGuide-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'header' => 'ID',
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 80,
                        'class' => '',
                    ),
                ),
                array(
                    'name' => 'msg_type',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'filter'=> CHtml::activeDropDownList($model, 'msg_type', [''=>'全部','1'=>'优惠活动','2'=>'系统通知','3'=>'电影活动']),
                    'value' => function($model) {
                        switch($model->msg_type){
                            case "1":
                                return "优惠活动";
                                break;
                            case "2":
                                return "系统通知";
                                break;
                            case "3":
                                return "电影活动";
                                break;
                            default:
                                return "其他渠道";
                                break;
                        }
                    },
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'content',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'header' => '推送平台',
                    'value' => function($model) {
                        $channelModel = new MessageNoticeChannel();
                        return $channelModel->getChannelById($model->id);
                    },
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'push_date',
                     'value'=>
                        function($model){
                            return  date('Y-m-d H:i:s',$model->push_date);
                        },
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),

                array(
                    'name' => 'state',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                    'filter'=> CHtml::activeDropDownList($model, 'state', [''=>'全部','0'=>'等待推送','1'=>'推送中','2'=>'推送完成','3'=>'推送失败']),
                    'value' => function($model) {
                        switch($model->state){
                            case "0":
                                return "等待推送";
                                break;
                            case "1":
                                return "推送中";
                                break;
                            case "2":
                                return "推送完成";
                                break;
                            case "3":
                                return "推送失败";
                                break;
                            default:
                                return "状态未知";
                                break;
                        }

                    }
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{update} {delete}',
                    'buttons'=>array()
                ),

            ),
        )); ?>
    </div>
</div>
<script>
    $(function () {
        $(document).on('mouseover', '.classIsOnline', function () {
            $(this).css("color", "#2a6496");
            $(this).css("cursor", "pointer");
        });


        $(document).on('mouseout', '.classIsOnline', function () {
            $(this).css("color", "#428bca");
        });

    })
</script>