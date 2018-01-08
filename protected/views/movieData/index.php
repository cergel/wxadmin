<?php
$this->breadcrumbs=array(
	'影片'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理影片</h1>
    <a class="btn btn-success" href="/movieData/create">创建影片</a>
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
				'name' => 'movie_name_chs',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
			'name' => 'age',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'longs',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'first_show',
			'value'=>'date("Y-m-d", $data->first_time)',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'country',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'in_short_remark',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'tags',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'actor',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'director',
            'htmlOptions' => array(
                'width' => 150
            )
		),
		array(
			'name' => 'version',
            'htmlOptions' => array(
                'width' => 150
            )
		),
		array(
				'name' => 'language',
				'htmlOptions' => array(
						'width' => 150
				)
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{update}',
			'buttons'=>array(
					'addimg' => array(
							'label'=>'添加图片',
							'url'=>'"/moviePoster/create/".$data->movie_id',
							'options' => array(
									'target' => '_blank',
							)
					),
			),
		),
	),
)); ?>
    </div>
</div>
