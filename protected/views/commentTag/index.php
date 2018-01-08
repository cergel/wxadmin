<?php
/* @var $this CommentTagController */
/* @var $dataProvider CActiveDataProvider */
$this->breadcrumbs = array(
    '标签管理' => array('index'),
    '列表',
);
?>
<div class="page-header">
    <h1>标签管理</h1>
    <a class="btn btn-success" href="/commentTag/create">添加标签</a>
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
                    'name' => 'tag_name',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'tag_content',
                    'htmlOptions' => array(
                        'width' => 100
                    )
                ),
                array(
                    'name' => 'comment_type',
                    'type' => 'raw',
                    'value' => '$data->comment_type == 1 ? \'好评\' : \'差评\'',
                    'filter' => CHtml::activeDropDownList($model, 'comment_type', (['' => '全部', '1' => '好评', '0' => '差评'])),
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'created',
                    'filter' => '',
                    'value' => 'date("Y-m-d H:i:s", $data->created)',
                    'htmlOptions' => array(
                        'width' => 80
                    )
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
                    'template' => '{update} {delete}',
                ),
            ),
        )); ?>
    </div>
</div>