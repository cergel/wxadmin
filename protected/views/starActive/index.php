<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 16:03
 */
$this->breadcrumbs = array(
    'StarActive' => array('index'),
    '明星见面会',
);
?>
<div class="page-header">
    <h1>明星见面会</h1>
    <a class="btn btn-success" href="/starActive/create">新建活动</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'starActive-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'a_id',
                    'htmlOptions' => array(
                        'width'   => 80
                    )
                ),
                array(
                    'name' => 'a_name',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'a_date',
                    'htmlOptions' => array(
                        //'width' => 80
                    )
                ),
                array(
                    'name' => 'a_create_name',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),

                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'template'=>'{view} {update} {delete}',
                    'headerHtmlOptions' => array('width'=>'100'),
                    'buttons'=>array(
                        'view'=>array(
                            'lable'=>'查看',
                            'url'=>'StarActive::model()->getActiveDetail($data->a_id)',
                        ),
                    ),
                ),
            ),
        )); ?>
    </div>
</div>
