<?php
$this->breadcrumbs=array(
    '发现活动题集'=>array('index'),
    '管理',
);
?>
<div class="page-header">
    <h1>发现活动题集管理</h1>
    <a class="btn btn-success" href="/questionSet/create">创建题集</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'movie-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'name',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'question',
                    'value' => 'QuestionSet::model()->getQuestion($data->question)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'num',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'create_time',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'80'),
                    'template' => '{update}  {delete}'
                ),
            ),
        )); ?>
    </div>
</div>
