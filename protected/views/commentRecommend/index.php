<?php
$this->breadcrumbs=array(
	'推荐评论'=>array('index'),
	'管理',
);
?>
<div class="page-header">
	<h1>推荐评论管理</h1>
	<a class="btn btn-success" href="/comment/index">新建推荐</a>
</div>

<div class="row">
	<div class="col-xs-12">
		<?php $this->widget('application.components.WxGridView', array(
			'id'=>'comment-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				array(
					'name' => 'id',
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
					'name' => 'ucid',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'content',
					'value' => 'Comment::model()->getCommentContent($data->id)',
					'filter' => '',
					'htmlOptions' => array(
							'width' => 380,
						),
				),
				array(
					'name' => 'status',
					'value' => '$data->status?"有效":"无效"',
					'filter'=> CHtml::activeDropDownList($model, 'status', array('' => '全部', '1'=>'有效', '0' => '无效')),
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'created',
					'type'=>'raw',
					'value' => 'date("Y-m-d H:i:s", $data->created)',
					'filter' => '',
					'htmlOptions' => array(
						'width' => 130
					),
				),
				array(
					'name' => 'end_time',
					'type'=>'raw',
					'value' => 'date("Y-m-d H:i:s", $data->end_time)',
					'filter' => '',
					'htmlOptions' => array(
						'width' => 130
					),
				),
				array(
					'header' => '操作',
					'class'=>'CButtonColumn',
					'headerHtmlOptions' => array('width'=>'80'),
					'template' => '{update} {delete} ',
					'buttons'=>array(
						'addcomment' => array(
							'label'=>'推荐',
							'url'=>'"/weixinComment/create/".$data->id',
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
