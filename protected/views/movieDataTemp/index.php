<?php
$this->breadcrumbs=array(
	'影片'=>array('index'),
	'管理',
);
?>
<div class="row">
    <div class="col-xs-12">
<?php
 $this->widget('application.components.WxGridView', array(
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
				'name' => 'movie_no',
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
				'name' => 'status',
				'value' => 'MovieDataTemp::model()->getStatusName($data->status)',
				'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','0'=>'删除','1'=>'未审核','2'=>'不通过审核','3'=>'通过审核'])),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
			'header' => '操作',
			'class'=>'CButtonColumn',
			'headerHtmlOptions' => array(
	
			),
			'template'=>'{view}	',
			'buttons'=>array(
					'view' => array(
							'label'=>'查看',
							'url'=>'"/movieDataTemp/update/" . $data->id',
							'options' => array(
									//'target' => '_blank',
									//'do' => 'dialogInfo',
									'value' => 'ActivePage::model()->getDialogInfo($data->iActivePageID,$data->iwx,$data->iqq,$data->imobile)',
							)
					),
			),
			'htmlOptions' => array(
					'width' => 100
			)
		),
	),
)); ?>
    </div>
</div>
