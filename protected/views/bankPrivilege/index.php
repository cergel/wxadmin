<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 14:16
 */
$this->breadcrumbs = array(
    '广告管理' => array('index'),
    '银行优惠'
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '.bank-privilege-status', function(){
        var id = $(this).attr('bank-privilege');
        $.ajax({
                url : '/bankPrivilege/_status_update?id='+id,
                type: 'post',
                dataType: 'text',
                success: function (data) {
                    if(data == '1') {
                        $('a.bank-privilege-status[bank-privilege='+id+']').html('正常');
                    } else {
                        $('a.bank-privilege-status[bank-privilege='+id+']').html('隐藏');
                    }
                }
        });
    });
    $(document).on('click', '.bank-privilege-sort', function(){
        $(this).select();
    }).on('change', '.bank-privilege-sort', function(){
        $.ajax({
            url : '/bankPrivilege/_sort_update?id='+$(this).attr('bank-privilege'),
            type: 'post',
            data: 'sort='+$(this).val(),
            dataType: 'text',
            beforeSend: function () {
            },
            success: function () {
                $.fn.yiiGridView.update('bank-privilege-grid');
            }
        });
    });
    ");

?>
<div class="page-header">
    <h1>银行优惠</h1>
    <a class="btn btn-success" href="/bankPrivilege/create">新建优惠</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'bank-privilege-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' =>40
                    )
                ),
                array(
                    'name' => 'b_id',
                    'value'=>'BankPrivilege::model()->getBankNameById($data->b_id)',
                    'filter' => CHtml::activeDropDownList($model, 'b_id',(array('' => '全部')+BankPrivilege::model()->getAllBankInfo())),
                    'htmlOptions' => array(
                        'width' =>100,
                    ),
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'start_time',
                    'htmlOptions' => array(
                        'width' => 120
                    )
                ),
                array(
                    'name' => 'end_time',
                    'htmlOptions' => array(
                        'width' => 120
                    )
                ),
                array(
                    'name' => 'cities',
                    'value'=>'BankPrivilege::model()->getSelectCity($data->id)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value'=>'\'<a href="javascript:void(0);" bank-privilege="\'.$data->id.\'" class="bank-privilege-status">\'.($data->status ? "正常" : "隐藏")',
                    'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', '1'=>'正常', '0' => '隐藏')),
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'sort',
                    'value'=>'\'<input type="text" value="\'.$data->sort.\'" bank-privilege="\'.$data->id.\'" class="bank-privilege-sort" />\'',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'width' => 30,

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