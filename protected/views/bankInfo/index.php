<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 12:09
 */
Yii::app()->clientScript->registerScript('index', "
$(document).on('click', '.bank-info-status', function(){
    var id = $(this).attr('bank-info');
    $.ajax({
                url : '/bankInfo/_status_update?id='+id,
                type: 'post',
                dataType: 'text',
                success: function (data) {
        if(data == '1') {
            $('a.bank-info-status[bank-info='+id+']').html('上线');
        } else {
            $('a.bank-info-status[bank-info='+id+']').html('下线');
        }
    }
        });
    });
");
$this->breadcrumbs = array(
    '广告管理' => array('index'),
    '银行信息'
);
?>
<div class="page-header">
    <h1>银行信息</h1>
    <a class="btn btn-success" href="/bankInfo/create">新建银行信息</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
    'id' => 'bank-info-grid',
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
            'name' => 'num',
            'htmlOptions' => array(
                'width' => 200,
            ),
        ),
        array(
            'name' => 'name',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'image',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value'=>'\'<a href="javascript:void(0);" bank-info="\'.$data->id.\'" class="bank-info-status">\'.($data->status ? "上线" : "下线")',
            'filter' => CHtml::activeDropDownList($model, 'status', array(''=>'全部','1'=>'上线','0'=>'下线')),
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'headerHtmlOptions' => array('width' => '100'),
        ),
    ),
)); ?>
    </div>
</div>