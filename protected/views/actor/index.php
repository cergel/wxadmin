<?php
/* @var $this ActorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '影人' => array('index'),
    '管理',
);

?>
<div class="page-header">
    <h1>管理影人</h1>
    <a class="btn btn-success" href="/actor/create">创建影人</a>
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
                    'name' => 'name_chs',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'name_eng',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'sex',
                    'type' => 'raw',
                    'value' => '$data->sex == 1 ? \'男\' : \'女\'',
                    'filter' => CHtml::activeDropDownList($model, 'sex', (['' => '全部', '1' => '男', '2' => '女'])),
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'base_like',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'like',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'created',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'updated',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{update}',
                ),
            ),
        )); ?>
    </div>
</div>