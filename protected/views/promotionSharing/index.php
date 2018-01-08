<?php
/* @var $this PromotionSharingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '拉新分享' => array('index'),
    '管理',
);
?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<div class="page-header">
    <h1>拉新分享</h1>
    <a class="btn btn-success" href="/promotionSharing/create">创建</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'promotionSharing-id',
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
                    'name' => 'create_user',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'for_user',
                    'value' => '$data->for_user == 1 ? \'新用户\' : \'新用户\'',
                    'filter' => CHtml::activeDropDownList($model, 'for_user', (['' => '全部', '1' => '新用户', '2' => '新用户'])),
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'created',
                    'value' => 'date("Y-m-d H:i:s",$data->created)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'updated',
                    'value' => 'date("Y-m-d H:i:s",$data->updated)',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'update_user',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{update} {delete}',
                ),
            ),
        )); ?>
    </div>
</div>