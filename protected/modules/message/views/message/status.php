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
                <a class="btn btn-success" href="/message/message/create">创建新的消息推送</a>
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
                    'name' => 'push_id',
                    'header' => '信鸽推送pushId',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'header' => '推送类型',
                    'name' => 'push_type',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value' => function($model) {
                        switch($model->push_type){
                            case "0":
                                return "主动";
                                break;
                            case "1":
                                return "被动";
                                break;
                            default:
                                return "未知";
                                break;
                        }
                    }
                ),
                array(
                    'header' => '消息类型',
                    'name' => 'content_type',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value' => function($model) {
                        switch($model->content_type){
                            case "1":
                                return "文本";
                                break;
                            case "2":
                                return "图文";
                                break;
                            default:
                                return "未知";
                                break;
                        }
                    }
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
                    'name' => 'channel',
                    'value' => function($model) {
                        switch($model->channel){
                            case "3":
                                return "微信电影票";
                                break;
                            case "8":
                                return "iOS";
                                break;
                            case "9":
                                return "Android";
                                break;
                            case "26":
                                return "手Q电影票";
                                break;
                            default:
                                return "其他渠道";
                                break;
                        }
                    },
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'push_date',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'tag',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'status',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                    'value' => function($model) {
                        switch($model->status){
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
                                return "推送失败或取消";
                                break;
                            default:
                                return "状态未知";
                                break;
                        }

                    }
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