<?php
$this->breadcrumbs=array(
	'图片'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理图片</h1>
    <a class="btn btn-success" href="/moviePoster/create">创建影片</a>
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
				'name' => 'movie_id',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
				'name' => 'poster_type',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
			'name' => 'size',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'url',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'status',
			'value'=>'date("Y-m-d", $data->status)',
            'htmlOptions' => array(
                'width' => 80
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
