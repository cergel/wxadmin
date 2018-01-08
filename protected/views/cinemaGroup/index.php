<?php
$this->breadcrumbs=array(
	'影院分组'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理影院分组</h1>
    <a class="btn btn-success" href="/cinemaGroup/create">创建影院分组</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'cinema-group-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'iGroupID',
            'htmlOptions' => array(
                'width' => 80
            ),
		),
		array(
			'name' => 'sName',
		),
		array(
			'name' => 'iCreated',
            'htmlOptions' => array(
                'width' => 150
            ),
		),
		array(
			'name' => 'iUpdated',
            'htmlOptions' => array(
                'width' => 150
            ),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'htmlOptions' => array(
				'width' => 80
			)
		),
	),
)); ?>
    </div>
</div>
