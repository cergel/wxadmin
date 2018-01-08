<?php
$this->breadcrumbs=array(
	'影片'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理影片</h1>
    <a class="btn btn-success" href="/app/movie/create">创建影片</a>
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
                'width' => 80
            )
		),
		array(
			'name' => 'baseScore',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'baseScoreCount',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'score',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'scoreCount',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'commentCount',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'wantCount',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'seenCount',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'created',
            'value' => 'date("Y-m-d H:i:s", $data->created)',
            'htmlOptions' => array(
                'width' => 150
            )
		),
		array(
			'name' => 'updated',
            'value' => 'date("Y-m-d H:i:s", $data->updated)',
            'htmlOptions' => array(
                'width' => 150
            )
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update}'
		),
	),
)); ?>
    </div>
</div>
