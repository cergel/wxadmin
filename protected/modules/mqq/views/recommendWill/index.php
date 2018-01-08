<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
    '内容',
);
?>

<div class="page-header">
    <h1>个人中心推荐位</h1>
    <a class="btn btn-success" href="/mqq/recommendWill/create">创建</a>
</div>


<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'active-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'order',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name' => 'movie_name',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name' => 'start_time',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                    'value' => 'date("Y-m-d H:i:s",$data->start_time)',
                ),
                array(
                    'name' => 'end_time',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                    'value' => 'date("Y-m-d H:i:s",$data->end_time)',
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{update}{delete}',
                    'buttons'=>array()
                ),
            ),
        )); ?>
    </div>
</div>
