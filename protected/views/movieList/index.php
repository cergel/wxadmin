<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'片单管理',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
    });
");
?>

<div class="page-header">
    <h1>片单管理</h1>
    <a class="btn btn-success" href="/MovieList/create">新建片单</a>
</div>


<div class="row">
    <div class="col-xs-12">
 <?php $this->widget('application.components.WxGridView', array(
        	'id'=>'active-grid',
        	'dataProvider'=>$model->search(),
        	'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 40,
                    ),
                ),
                 array(
                    'name' => 'title',
                    'header' => '片单名',
                    'htmlOptions' => array(
                        'width' => 180,
                    ),
                ),
                array(
                    'name'=>'author',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'movie_num',
                    'htmlOptions' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'name' => 'collect_num_really',
                    'htmlOptions' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'name' => 'read_num_really',
                    'htmlOptions' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'name'=>'state',
                    'filter'=> CHtml::activeDropDownList($model, 'state', [''=>'全部','1'=>'上线','0'=>'下线']),
                    'value'=>'$data->state?"上线":"下线"',
                    'htmlOptions' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'name'=>'online_time',
                    'value' => '$data->online_time ? date("Y-m-d H:i:s", $data->online_time):""',
                    'htmlOptions' => array(
                        'width' => 90,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'120'),
                    'template' => '{up} {down} {update} {delete}',
                    'buttons'=>array( 
                        'up' => array(
                            'label' => '上线',
                            'url'=>'"/movieList/updown/".$data->id',
                            'visible' => '$data->state == 0',
                        ),
                        'down' => array(
                            'label' => '下线',
                            'url' => '"/movieList/updown/".$data->id',
                            'visible' => '$data->state == 1',
                        ),
                        )
                ),
            ),
        )); ?>
    </div>
</div>