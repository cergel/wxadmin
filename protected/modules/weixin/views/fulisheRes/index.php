<?php
$this->breadcrumbs=array(
    '手Q'=>array('default/index'),
	'福利社配置'=>array('index'),
	'编辑',
);
?>
<div class="page-header">
    <h1>
        福利社配置
    </h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>