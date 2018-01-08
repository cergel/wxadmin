<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 16:39
 */

Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.icon-config-status', function(){
        var id = $(this).attr('icon-config');
        $.ajax({
            url : '/app/iconConfig/_status_update?id='+id,
            type: 'post',
            dataType: 'text',
            beforeSend: function () {
            },
            success: function (data) {
                if(data == '1') {
                    $('a.icon-config-status[icon-config='+id+']').html('发布');
                } else {
                    $('a.icon-config-status[icon-config='+id+']').html('未发布');
                }
            }
        });
    });
");
$this->breadcrumbs=array(
    '可配置资源管理'=>array('index'),
    '管理',
);
?>
<div class="page-header">
    <h1>可配置资源</h1>
    <a class="btn btn-success" href="/app/iconConfig/create">创建资源</a>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'icon-config-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width'   => 50
                    )
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'type',
                    'value'=>'IconConfig::model()->getTypeName($data->type)',
                    'filter'=> CHtml::activeDropDownList($model, 'type', array('' => '全部','1'=>'ICON组合','2'=>'Loading图')),
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'platform',
                    'value'=>'IconConfig::model()->getPlatform($data->platform)',
                    'filter'=> CHtml::activeDropDownList($model, 'platform', array('' => '全部','8' => 'IOS', '9' => 'Android')),
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'start_time',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'end_time',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'created_time',
                    'value'=>'IconConfig::model()->int2date($data->created_time)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'modified_time',
                    'value'=>'IconConfig::model()->int2date($data->modified_time)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value'=>'\'<a href="javascript:void(0);" icon-config="\'.$data->id.\'" class="icon-config-status">\'.($data->status ? "发布" : "未发布")',
                    'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', '1'=>'发布', '0' => '未发布')),
                    'htmlOptions' => array(
                        'width' => 70
                    )
                ),

                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'template'=>'{update} {delete}',
                    'headerHtmlOptions' => array('width'=>'80'),
                ),
            ),
        )); ?>
    </div>
</div>