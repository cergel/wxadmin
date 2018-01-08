<?php
/* @var $this FilmFestivalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '电影节' => array('index'),
    '管理',
);
?>
<style type="text/css">
    .error{
        color: red;
    }
</style>
<div class="page-header">
    <h1>电影节</h1>
    <a class="btn btn-success" href="/filmFestival/create">创建</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'filmFestival-id',
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
                    'name' => 'filmfest_name',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'url_param',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'bisServerid',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'create_user',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'start_time',
                    'type' => 'raw',
                    'value' => '\'<div class="\'.FilmFestival::model()->getStatusList($data->start_time,$data->end_time).\'">\'.date(\'Y-m-d H:i:s\',$data->start_time).\'</div>\'',
                    'htmlOptions' => array(
                        'width' => 100,
                        'class' => 'start_time',
                    ),
                ),
                array(
                    'name' => '状态',
                    'value' => 'FilmFestival::model()->getStatus($data->start_time,$data->end_time)',
                    'filter' => CHtml::activeDropDownList($model, 'start_time', (['' => '全部', '1' => '上线', '2' => '下线'])),
                    'htmlOptions' => array(
                        'width' => 100
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
