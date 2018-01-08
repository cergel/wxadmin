<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/4
 * Time: 14:42
 */
$this->breadcrumbs=array(
    '明星见面会'=>array('index'),
    '详情',
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('form', "
   $('.date-timepicker').datetimepicker({
            format:\"YYYY-MM-DD\"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
");

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'star-active-sche-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>
<div class="page-header">
    <h1>明星见面会详情#<?php echo $a_id; ?></h1>
    <a class="btn btn-success"href="/starActive/sche?aid=<?php echo $a_id; ?>">新建排期</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'starActive-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 's_id',
                    'htmlOptions' => array(
                        'width'   => 60
                    )
                ),
                array(
                    'name' => 's_sche_id',
                    'htmlOptions' => array(
                        'width'   => 60
                    )
                ),
                array(
                    'name' => 's_cinema_id',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name' => 's_cinema_name',
                    'htmlOptions' => array(
                        'width' => 60,
                    ),
                ),
                array(
                    'name' => 's_movie_id',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 's_movie_name',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 's_sche_type',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 's_sche_room',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 's_start_time',
                    'type'=>'raw',
                    'value' => 'date("Ymd H:i:s", $data->s_start_time)',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 's_end_time',
                    'type'=>'raw',
                    'value' => 'date("Ymd H:i:s", $data->s_end_time)',
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'template'=>'{delete}',
                    'headerHtmlOptions' => array('width'=>'60'),
                    'buttons'=>array(
                        'delete'=>array(
                            'lable'=>'删除',
                            'url'=>'StarActiveSche::model()->getActiveDelDetail($data->s_id)',
                        ),
                    ),
                ),
            ),
        )); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
