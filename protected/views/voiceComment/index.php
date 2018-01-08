<?php
$this->breadcrumbs = array(
    '评论' => array('index'),
    '主创说管理',
);
?>
<div class="page-header">
    <h1>主创说管理</h1>
    <a class="btn btn-success" href="/voiceComment/create">新建主创说</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'comment-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 30
                    )
                ),
                array(
                    'name' => 'movie_id',
                    'htmlOptions' => array(
                        'width' => 30
                    )
                ),
                array(
                    'name' => 'movie_name',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'actor_id',
                    'htmlOptions' => array(
                        'width' => 30,
                    ),
                ),
                array(
                    'name' => 'nick_name',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'header' => '评论id',
                    'value' => '$data->id',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'favor_count',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'order',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->status == 1 ? \'是\' : \'否\'',
                    'filter' => CHtml::activeDropDownList($model, 'status', (['' => '全部', '1' => '是', '0' => '否'])),
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'clicks',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 40
                    ),
                ),
                array(
                    'name' => 'created',
                    'type' => 'raw',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'filter' => '',
                    'htmlOptions' => array(
                        'width' => 100
                    ),
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{update} {del} ',
                    'buttons' => array(
                        'del' => array(
                            'label' => '删除',
                            'url' => '"/voiceComment/doDelete/".$data->id',
                        ),
                    )
                ),
            ),
        )); ?>
    </div>
</div>
