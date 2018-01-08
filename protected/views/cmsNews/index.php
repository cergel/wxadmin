<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs=array(
	'资讯',
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
    });
");
?>

<div class="page-header">
    <h1>资讯发现</h1>
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
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'a_id',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'movie_id',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name'=>'f_title',
                    'filter'=>'',
                    'value'=>'ActiveCms::model()->getCmsTitle($data->a_id)',
                    'htmlOptions' => array(
                        'width' => 300,
                    ),
                ),
                array(
                    'name' => 'up_time',
                    'value' => 'date("Y-m-d H:i:s", $data->up_time)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name'=>'status',
                    'filter'=> CHtml::activeDropDownList($model, 'status', [''=>'全部','1'=>'上线','0'=>'下线']),
                    'value'=>'$data->status?"上线":"下线"',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => '{delete}',
                    'buttons'=>array()
                ),
            ),
        )); ?>
    </div>
</div>
