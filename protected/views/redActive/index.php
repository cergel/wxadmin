<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 11:28
 */
$this->breadcrumbs = array(
    'RedActive' => array('index'),
    '红点活动',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.redActive-status', function(){
        var id = $(this).attr('redActive');
        $.ajax({
                url : '/redActive/_status_update?id='+id,
                type: 'post',
                dataType: 'text',
                success: function (data) {
                    if(data == '1') {
                        $('a.redActive-status[redActive='+id+']').html('开启');
                    } else {
                        $('a.redActive-status[redActive='+id+']').html('关闭');
                    }
                }
        });
    });
    ");

?>
<div class="page-header">
    <h1>红点活动</h1>
    <a class="btn btn-success" href="/redActive/create">新建活动</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'redActive-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'a_id',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'a_name',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'local',
                    'value' => 'RedActive::model()->findLocal($data->a_id)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 500
                    )
                ),
                array(
                    'name' => 'a_start_time',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'a_end_time',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 'a_status',
                    'type' => 'raw',
                    'value'=>'\'<a href="javascript:void(0);" redActive="\'.$data->a_id.\'" class="redActive-status">\'.($data->a_status ? "开启" : "关闭")',
                    'filter' => CHtml::activeDropDownList($model, 'a_status', array('' => '全部', '1' => '开启', '0' => '关闭')),
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'template' => '{update} {delete}',
                    'headerHtmlOptions' => array('width' => '100'),
                ),
            ),
        )); ?>
    </div>
</div>