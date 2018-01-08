<?php
/* @var $this UserGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '用户权限分组' => array('index'),
);
?>
<div class="page-header">
    <h1>用户权限分组</h1>
    <a class="btn btn-success" href="/userGroup/create">新建分组</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'comment-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 30
                    )
                ),
                array(
                    'name' => 'groupName',
                    'htmlOptions' => array(
                        'width' => 30
                    )
                ),
                array(
                    'name' => 'blackOrWhite',
                    'filter' => '',
                    'value' => '$data->blackOrWhite == 1 ? \'黑名单\' : \'白名单\'',
                    'filter' => CHtml::activeDropDownList($model, 'blackOrWhite', (['' => '全部', '1' => '黑名单', '2' => '白名单'])),
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'createUser',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'created',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'htmlOptions' => array(
                        'width' => 100
                    ),
                ),
                array(
                    'name' => 'updated',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{update} ',
                ),
            ),
        )); ?>
    </div>
</div>