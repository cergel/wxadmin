<?php
$this->breadcrumbs=array(
	'消息中心'=>array('index'),
	'新建消息',
);
?>
<div class="page-header">
    <h1>添加消息推送</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'bool' => TRUE)); ?>