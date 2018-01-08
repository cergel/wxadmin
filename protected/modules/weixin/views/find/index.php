<?php
$this->breadcrumbs = array(
    '微信发现导流'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);

?>
<div class="page-header">
    <h1>微信发现导流</h1>
    <a class="btn btn-success" href="/weixin/find/create">创建</a>
</div>


<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'active-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'startTime',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'endTime',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                   // 'value'=>'$data->formatData($data->start_time)',
                ),
                array(
                    'name' => 'content',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                   // 'value'=>'$data->formatData($data->end_time)',
                ),
                array(
                    'name' => 'status',
                    'value'=>'($data->status ? "上线" : "下线")',
                    'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', '1'=>'上线', '0' => '下线')),
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'created',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'updated',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
//                array(
//                    'name' => 'content',
//                    'htmlOptions' => array(
//                        'width' => 80,
//                    ),
//                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => ' {update} {delete}',
                ),
            ),
        )); ?>
    </div>
    <div id="dialogInfo" style="display: block"></div>
</div>
