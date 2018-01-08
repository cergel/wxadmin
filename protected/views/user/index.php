<?php
$this->breadcrumbs=array(
	'用户'
);
?>
<div class="page-header">
    <h1>管理用户</h1>
    <a class="btn btn-success" href="/user/create">创建用户</a>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->widget('application.components.WxGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'iUserID',
            'htmlOptions' => array(
                'width' => 80
            ),
        ),
        array(
            'name' => 'sEmail',
            'htmlOptions' => array(
                //'width' => 80
            ),
        ),
        array(
            'name' => 'sUserName',
            'htmlOptions' => array(
                //'width' => 80
            ),
        ),
        array(
            'name' => 'sNickName',
            'htmlOptions' => array(
                //'width' => 80
            ),
        ),
        array(
            'name' => 'sLastIp',
            'htmlOptions' => array(
                'width' => 150
            ),
        ),
        array(
            'header' => '分组名称',
            'type' => 'raw',
            'value' => 'UserGroup::model()->getGroupName($data->sGroupId)',
            'htmlOptions' => array(
                'width' => 150
            ),
        ),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template'=>'{update} {delete}',
		),
	),
)); ?>
    </div>
</div>