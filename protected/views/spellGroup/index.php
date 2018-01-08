<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/8/11
 * Time: 16:12
 */
Yii::app()->clientScript->registerScript('index', "
$(document).on('click', '.spell-group-status', function(){
    var id = $(this).attr('spell-group');
    $.ajax({
                url : '/spellGroup/_status_update?id='+id,
                type: 'post',
                dataType: 'text',
                success: function (data) {
        if(data == '1') {
            $('a.spell-group-status[spell-group='+id+']').html('上线');
        } else {
            $('a.spell-group-status[spell-group='+id+']').html('下线');
        }
    }
        });
    });
");
$this->breadcrumbs = array(
    '拼团活动' => array('index'),
);
?>
<div class="page-header">
    <h1>拼团活动设置</h1>
    <a class="btn btn-success" href="/spellGroup/create">新建活动</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'spell-group-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'active_id',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'movie_name',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'team_status',
                    'value' => 'SpellGroup::model()->getTeamStatusCount($data->active_id)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'created_time',
                    'value' => 'SpellGroup::model()->int2date($data->created_time)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value'=>'\'<a href="javascript:void(0);" spell-group="\'.$data->active_id.\'" class="spell-group-status">\'.($data->status ? "上线" : "下线")',
                    'filter' => CHtml::activeDropDownList($model, 'status', array(''=>'全部','1'=>'上线','0'=>'下线')),
                    'htmlOptions' => array(
                        'width' => 80
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