<?php
/* @var $this CinemaHallFeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '影城' => array('index'),
    '特效厅管理',
);
?>
<div class="page-header">
    <h1>特效厅管理</h1>
    <a class="btn btn-danger" id="Pull" href="javascript:void(0);" style="right:120px">拉取详情</a>
    <a class="btn btn-success" href="/cinemaHallFeature/create">创建特效厅</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'actor-id',
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
                    'name' => 'cinema_no',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'cinema_name',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'hall_no',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'hall_name',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'base_zan_num',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'zan_num',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'count_zan',
                    'value' => '$data->zan_num+$data->base_zan_num',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'step_num',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'count_num',
                    'value' => '$data->zan_num+$data->base_zan_num+$data->step_num',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'created',
                    'filter' => '',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'updated',
                    'filter' => '',
                    'value' => 'date("Y-m-d H:i:s", $data->updated)',
                    'htmlOptions' => array(
                        'width' => 80
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
<script type="text/javascript">
    $(function () {
        $("#Pull").click(function () {
            var r = confirm("确定拉取么?请勿频繁操作!");
            if (r == true) {
                $.post('<?php echo Yii::app()->getController()->createUrl('cinemaHallFeature/ajaxPull') ?>',
                    '',
                    function (data) {
                        alert(data.msg);
                        if (data.code == 0) {
                            location.reload();
                        }
                    }
                    , "json");
            }
        });
    });
</script>