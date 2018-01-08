<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
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
    '作者管理' => array('index'),
);
?>
<div class="page-header">
    <h1>作者列表</h1>
    <a class="btn btn-success" href="/author/create">新建作者</a>
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
            'name' => 'name_author',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'head_img',
            'type' => 'raw',
            'value'=>'\'<a href="\'.$data->head_img.\'" target="_blank" class="bank-info-status"><img src="\'.$data->head_img.\'" class="bank-info-status" width="60px">\'',
            'filter' => '',
            'htmlOptions' => array(
                'width' => 60
            )
        ),
        array(
            'name' => 'qr_img',
            'type' => 'raw',
            'value'=>'\'<a href="\'.$data->qr_img.\'" target="_blank" class="bank-info-status"><img src="\'.$data->qr_img.\'" class="bank-info-status" width="60px">\'',
            'filter' => '',
            'htmlOptions' => array(
                'width' => 60
            )
        ),
        array(
            'name' => 'summary',
            'htmlOptions' => array(
                'width' => 150
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