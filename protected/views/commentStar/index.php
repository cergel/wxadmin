<?php
$this->breadcrumbs=array(
	'明星'=>array('index'),
	'管理',
);
?>
<div class="page-header">
	<h1>明星管理</h1>
	<a class="btn btn-success" href="/commentStar/create">新建明星</a>
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
					'name' => 'ucid',
					'htmlOptions' => array(
						'width' => 30
					)
				),
				array(
					'name' => 'nickName',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'summary',
					'htmlOptions' => array(
						'width' => 140
					)
				),
				array(
					'name' => 'tag',
					'value' => 'CommentStar::model()->getTagInfo($data->id)',
					'filter' => '',
					'htmlOptions' => array(
							'width' => 140,
						),
				),
				array(
					'name' => 'updated',
					'type'=>'raw',
					'value' => 'date("Y-m-d H:i:s", $data->updated)',
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
