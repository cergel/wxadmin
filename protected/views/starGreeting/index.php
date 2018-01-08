<?php
/* @var $this StarGreetingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '明星问候' => array('index'),
    '管理',
);
?>
<div class="page-header">
    <h1>明星问候</h1>
    <a class="btn btn-success" href="/starGreeting/create">添加明星问候</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'actor-id',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'start_time',
                    'value' => 'date("Y-m-d H:i:s", $data->start_time)',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'end_time',
                    'value' => 'date("Y-m-d H:i:s", $data->end_time)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'type',
                    'type' => 'raw',
                    'value' => '$data->type == 1 ? \'平日\' : \'全天\'',
                    'filter' => CHtml::activeDropDownList($model, 'type', (['' => '全部', '1' => '平日', '2' => '全天'])),
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'created',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'updated',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value' => '$data->status == 1 ? \'发布中\' : \'未发布\'',
                    'filter' => CHtml::activeDropDownList($model, 'status', (['' => '全部', '1' => '上线中', '0' => '下线中'])),
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{update} {delete} {edit1} {edit2}',
                    'buttons' => array(
                        'edit1' => array(
                            'label' => '下线',
                            'url' => '"/starGreeting/ediType/".$data->id',
                            'visible' => '$data->status == 1'
                        ),
                        'edit2' => array(
                            'label' => '上线',
                            'url' => '"/starGreeting/ediType/".$data->id',
                            'visible' => '$data->status != 1'
                        ),
                    )
                ),
            ),
        )); ?>
    </div>
</div>