<?php
$this->breadcrumbs=array(
    '影城公告'=>array('index'),
    '管理',
);
Yii::app()->clientScript->registerScript('index', "
    // 点击复制
    $(document).on('click', '.cinema_notification_duplicate', function(e){
        $.ajax({
            url : $(this).attr('href'),
            type: 'post',
            dataType: 'text',
            success: function (data) {
                $.fn.yiiGridView.update('cinema-notification-grid');
            }
        });
        return false;
    });
");
?>
<div class="page-header">
    <h1>管理影城公告</h1>
    <a class="btn btn-success" href="/cinemaNotification/create">创建公告</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
    'id'=>'cinema-notification-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name' => 'iNotificationID',
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'sName',
            'htmlOptions' => array(
                //'width' => 300
            )
        ),
        array(
            'name'=>'iShow',
            'value'=>'CinemaNotification::model()->getShow($data->iShow)',
            'filter'=> CHtml::activeDropDownList($model, 'iShow',['' => '全部'] + $model->getShow() ),
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'name' => 'iStartAt',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name' => 'iEndAt',
            'htmlOptions' => array(
                'width' => 150
            )
        ),
        array(
            'name'=>'iStatus',
            'value'=>'$data->iStatus ? "开启" : "关闭"',
            'filter'=> CHtml::activeDropDownList($model, 'iStatus', array('' => '全部', '1'=>'开启', '0' => '关闭')),
            'htmlOptions' => array(
                'width' => 80
            )
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update} {duplicate} {delete}',
            'buttons' => array
            (
                'duplicate' => array
                (
                    'label'=>'复制',
                    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
                    'url'=>'Yii::app()->createUrl("cinemaNotification/_duplicate", array("id"=>$data->iNotificationID))',
                    'options' => array(
                        'class' => 'cinema_notification_duplicate'
                    ),
                )
            ),
            'htmlOptions' => array(
                'width' => 100
            ),
        ),
    ),
)); ?>
    </div>
</div>